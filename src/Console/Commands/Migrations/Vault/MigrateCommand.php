<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Vault;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\Base\BaseMigrateCommand;
use Gianfriaur\PackageLoader\Migration\PackageMigrator;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Console\View\Components\Info;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\NoPendingMigrations;
use Illuminate\Database\SQLiteDatabaseDoesNotExistException;
use Illuminate\Support\Collection;
use PDOException;
use Throwable;

class MigrateCommand extends BaseMigrateCommand
{
    protected PackageMigrator $migrator;

    protected Dispatcher $dispatcher;

    protected PackageProviderServiceInterface $packageProviderService;


    protected array $packages = [];

    /**
     * Create a new migration command instance.
     */
    public function __construct(
        PackageMigrator $migrator,
        Dispatcher $dispatcher,
        PackageProviderServiceInterface $packageProviderService)
    {
        parent::__construct();

        $this->migrator = $migrator;
        $this->dispatcher = $dispatcher;
        $this->packageProviderService= $packageProviderService;

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $packages = $this->option('package');

        if (count($packages)===0) {
            $packages = array_keys($this->packageProviderService->getPackageProviders());
        }

        $this->packages = $packages;

        if (! $this->confirmToProceed()) {
            return 1;
        }

        $this->migrator->usingConnection($this->option('database'), function () {
            $this->prepareDatabase();

            $migrations= new Collection();

            foreach ($this->packages as $package) {
                $ran = $this->migrator->getRepository()->getRan($package);
                $batches = $this->migrator->getRepository()->getMigrationBatches($package);
                $migrations = $migrations->merge($this->getStatusFor($ran, $batches, $package));
            }

            if (count($migrations)===0){
                $this->fireMigrationEvent(new NoPendingMigrations('up'));

                $this->write(Info::class, 'Nothing to migrate');
            }else{
                $this->fireMigrationEvent(new MigrationsStarted('up'));

                $this->write(Info::class, 'Running migrations.');

                foreach ( $this->packages as $package) {

                    $migrations = $this->migrator->setOutput($this->output)
                        ->run($this->getMigrationPaths($package), [
                            'pretend' => $this->option('pretend'),
                            'step'    => $this->option('step'),
                            'package' => $package,
                        ],$package);

                }

                $this->fireMigrationEvent(new MigrationsEnded('up'));
            }

            $this->output->writeln('');

        });

        return 0;
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        if (! $this->repositoryExists()) {
            $this->components->info('Preparing database.');

            $this->components->task('Creating package migration table', function () {
                return $this->callSilent('package:migrate:install', array_filter([
                        '--database' => $this->option('database'),
                    ])) == 0;
            });

            $this->newLine();
        }

    }

    /**
     * Determine if the migrator repository exists.
     *
     * @return bool
     */
    protected function repositoryExists()
    {
        return retry(2, fn () => $this->migrator->repositoryExists(), 0, function ($e) {
            try {
                if ($e->getPrevious() instanceof SQLiteDatabaseDoesNotExistException) {
                    return $this->createMissingSqliteDatbase($e->getPrevious()->path);
                }

                $connection = $this->migrator->resolveConnection($this->option('database'));

                if (
                    $e->getPrevious() instanceof PDOException &&
                    $e->getPrevious()->getCode() === 1049 &&
                    $connection->getDriverName() === 'mysql') {
                    return $this->createMissingMysqlDatabase($connection);
                }

                return false;
            } catch (Throwable) {
                return false;
            }
        });
    }

    /**
     * Create a missing SQLite database.
     *
     * @param  string  $path
     * @return bool
     */
    protected function createMissingSqliteDatbase($path)
    {
        if ($this->option('force')) {
            return touch($path);
        }

        if ($this->option('no-interaction')) {
            return false;
        }

        $this->components->warn('The SQLite database does not exist: '.$path);

        if (! $this->components->confirm('Would you like to create it?')) {
            return false;
        }

        return touch($path);
    }

    /**
     * Create a missing MySQL database.
     *
     * @return bool
     */
    protected function createMissingMysqlDatabase($connection)
    {
        if ($this->laravel['config']->get("database.connections.{$connection->getName()}.database") !== $connection->getDatabaseName()) {
            return false;
        }

        if (! $this->option('force') && $this->option('no-interaction')) {
            return false;
        }

        if (! $this->option('force') && ! $this->option('no-interaction')) {
            $this->components->warn("The database '{$connection->getDatabaseName()}' does not exist on the '{$connection->getName()}' connection.");

            if (! $this->components->confirm('Would you like to create it?')) {
                return false;
            }
        }

        try {
            $this->laravel['config']->set("database.connections.{$connection->getName()}.database", null);

            $this->laravel['db']->purge();

            $freshConnection = $this->migrator->resolveConnection($this->option('database'));

            return tap($freshConnection->unprepared("CREATE DATABASE IF NOT EXISTS `{$connection->getDatabaseName()}`"), function () {
                $this->laravel['db']->purge();
            });
        } finally {
            $this->laravel['config']->set("database.connections.{$connection->getName()}.database", $connection->getDatabaseName());
        }
    }

    /**
     * Fire the given event for the migration.
     */
    public function fireMigrationEvent($event) : void
    {
        // if ($this->events) {
        //     $this->events->dispatch($event);
        //   }
    }

    /**
     * Get the status for the given run migrations.
     *
     * @param array $ran
     * @param array $batches
     * @param string $package
     * @return Collection
     * @noinspection DuplicatedCode
     */
    protected function getStatusFor(array $ran, array $batches, string $package)
    {
        return Collection::make($this->getAllMigrationFiles($package))
            ->map(function ($migration) use ($ran, $batches,$package) {
                $migrationName = $this->migrator->getMigrationName($migration);
                return [$migrationName, !in_array($migrationName, $ran), $package];
            })->filter(function ($migration){return $migration[1];});
    }

    protected function getAllMigrationFiles(string $package): array
    {
        return $this->migrator->getMigrationFiles($this->getMigrationPaths($package));
    }

    /**
     * Write to the console's output.
     *
     * @param  string  $component
     * @param  array<int, string>|string  ...$arguments
     * @return void
     */
    protected function write($component, ...$arguments)
    {
        if ($this->output && class_exists($component)) {
            (new $component($this->output))->render(...$arguments);
        } else {
            foreach ($arguments as $argument) {
                if (is_callable($argument)) {
                    $argument();
                }
            }
        }
    }
}