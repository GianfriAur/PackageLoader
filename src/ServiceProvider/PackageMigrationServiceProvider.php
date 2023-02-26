<?php

namespace Gianfriaur\PackageLoader\ServiceProvider;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\InstallCommand;
use Gianfriaur\PackageLoader\Migration\PackageMigrator;
use Gianfriaur\PackageLoader\Service\MigrationStrategyService\MigrationStrategyServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\ServiceProvider;

class PackageMigrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     */
    protected array $commands = [
        'MigrateInstall' => InstallCommand::class,
    ];


    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerRepository();

        $this->registerMigrator();

        $this->registerCreator();

        $this->registerCommands($this->commands);
    }

    protected function registerRepository(): void
    {
        $this->app->singleton('package_loader.migration.repository', function ($app) {
            /** @var MigrationStrategyServiceInterface $strategy */
            $strategy = $app->get('package_loader.migration.strategy');
            return $strategy->getMigrationRepository();
        });
    }

    protected function registerMigrator(): void
    {
        $this->app->singleton('package_loader.migrator', function ($app) {
            $repository = $app['package_loader.migration.repository'];
            return new PackageMigrator($repository, $app['db'], $app['files'], $app['events']);
        });
    }

    protected function registerCreator(): void
    {
        $this->app->singleton('package_loader.migration.creator', function ($app) {
            return new MigrationCreator($app['files'], $app->basePath('stubs'));
        });
    }
    /**
     * Register the given commands.
     *
     * @param array $commands
     * @return void
     */
    protected function registerCommands(array $commands): void
    {
        foreach (array_keys($commands) as $command) {
            $this->{"register{$command}Command"}();
        }
        $this->commands(array_values($this->commands));
    }

    protected function registerMigrateInstallCommand(): void
    {
        $this->app->singleton(InstallCommand::class, function ($app) {
            return new InstallCommand($app[ 'package_loader.migration.repository' ]);
        });
    }

    public function provides(): array
    {
        return array_merge([
            'package_loader.migrator', 'package_loader.migration.repository', 'package_loader.migration.creator',
        ], array_values($this->commands));
    }


}