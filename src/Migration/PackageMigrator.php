<?php

namespace Gianfriaur\PackageLoader\Migration;

use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Illuminate\Console\View\Components\Info;
use Illuminate\Console\View\Components\Task;
use Illuminate\Console\View\Components\TwoColumnDetail;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\NoPendingMigrations;
use Illuminate\Database\Migrations\Migrator as BaseMigrator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class PackageMigrator extends BaseMigrator
{
    /**
     * The migration repository implementation.
     *
     * @var PackageMigrationRepositoryInterface
     */
    protected $repository;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct(
        PackageMigrationRepositoryInterface $repository,
        Resolver $resolver,
        Filesystem $files,
        Dispatcher $dispatcher = null)
    {
        $this->files = $files;
        $this->events = $dispatcher;
        $this->resolver = $resolver;
        $this->repository = $repository;
    }

    public function getRepository(): PackageMigrationRepositoryInterface
    {
        return $this->repository;
    }

    public function hasRunAnyMigrations($package=null): bool
    {
        return $this->repositoryExists() && count($this->repository->getRan($package)) > 0;
    }

    public function run($paths = [], array $options = [],$package=null)
    {
        $files = $this->getMigrationFiles($paths);

        $this->requireFiles($migrations = $this->pendingMigrations(
            $files, $this->repository->getRan($package)
        ));

        $this->runPending($migrations, $options,$package);

        return $migrations;
    }


    public function runPending(array $migrations, array $options = [],$package=null)
    {

        $batch = $this->repository->getNextBatchNumber($package);

        $pretend = $options['pretend'] ?? false;

        $step = $options['step'] ?? false;


        foreach ($migrations as $file) {
            $this->runUp($file, $batch, $pretend,$package);

            if ($step) {
                $batch++;
            }
        }

    }
    /** @noinspection PhpParamsInspection */
    protected function runUp($file, $batch, $pretend,$package=null)
    {

        $migration = $this->resolvePath($file);

        $name = $this->getMigrationName($file);

        if ($pretend) {
            $this->pretendToRun($migration, 'up');
            return;
        }

        $this->write(Task::class, '<fg=green>'.$package.'</> - '.$name, fn () => $this->runMigration($migration, 'up'));

        $this->repository->log($package, $name, $batch);
    }

    protected function getMigrationsForRollback(array $options): array
    {
        if (($steps = $options['step'] ?? 0) > 0) {
            return $this->repository->getMigrations($options['package'],$steps);
        }

        return $this->repository->getLast($options['package']);
    }

    protected function rollbackMigrations(array $migrations, $paths, array $options)
    {
        $rolledBack = [];

        $this->requireFiles($files = $this->getMigrationFiles($paths));

        $this->fireMigrationEvent(new MigrationsStarted('down'));

        $this->write(Info::class, 'Rolling back migrations.');

        foreach ($migrations as $migration) {
            $migration = (object) $migration;

            if (! $file = Arr::get($files, $migration->migration)) {
                $this->write(TwoColumnDetail::class, $migration->migration, '<fg=yellow;options=bold>Migration not found</>');

                continue;
            }

            $rolledBack[] = $file;

            $this->runDownPackage(
                $file, $migration,
                $options['pretend'] ?? false,
                $options['package']
            );
        }

        $this->fireMigrationEvent(new MigrationsEnded('down'));

        return $rolledBack;
    }

    /** @noinspection PhpParamsInspection */
    private function runDownPackage($file, $migration, $pretend, $package)
    {

        $instance = $this->resolvePath($file);

        $name = $this->getMigrationName($file);

        if ($pretend) {
             $this->pretendToRun($instance, 'down');
            return;
        }

        $this->write(Task::class, '<fg=green>'.$package.'</> - '.$name, fn () => $this->runMigration($instance, 'down'));

        $this->repository->delete($package,$migration);
    }

    public function resetPackage(string $package, $paths = [], $pretend = false)
    {
        $migrations = array_reverse($this->repository->getRan($package));

        if (count($migrations) === 0) {
            $this->write(Info::class, 'Nothing to rollback for <fg=green>'.$package.'</>.');

            return [];
        }

        return tap($this->resetMigrationsPackage($package,$migrations, $paths, $pretend), function () {
            if ($this->output) {
                $this->output->writeln('');
            }
        });
    }

    protected function resetMigrationsPackage(string $package,array $migrations, array $paths, $pretend = false)
    {
        $migrations = collect($migrations)->map(function ($m) {
            return (object) ['migration' => $m];
        })->all();

        $options = compact('pretend');
        $options['package'] = $package;

        return $this->rollbackMigrations(
            $migrations, $paths, $options,
        );
    }

}