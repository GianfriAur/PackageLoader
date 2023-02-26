<?php

namespace Gianfriaur\PackageLoader\Migration;

use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Illuminate\Console\View\Components\Task;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Migrations\Migrator as BaseMigrator;
use Illuminate\Filesystem\Filesystem;

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

    /**
     * Get the migration repository instance.
     *
     * @return PackageMigrationRepositoryInterface
     */
    public function getRepository(): PackageMigrationRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Determine if any migrations have been run.
     *
     * @param null $package
     * @return bool
     */
    public function hasRunAnyMigrations($package=null): bool
    {
        return $this->repositoryExists() && count($this->repository->getRan($package)) > 0;
    }

    /**
     * Run the pending migrations at a given path.
     *
     * @param array $paths
     * @param array $options
     * @param null $package
     * @return array
     */
    public function run($paths = [], array $options = [],$package=null)
    {
        // Once we grab all of the migration files for the path, we will compare them
        // against the migrations that have already been run for this package then
        // run each of the outstanding migrations against a database connection.
        $files = $this->getMigrationFiles($paths);

        $this->requireFiles($migrations = $this->pendingMigrations(
            $files, $this->repository->getRan($package)
        ));

        // Once we have all these migrations that are outstanding we are ready to run
        // we will go ahead and run them "up". This will execute each migration as
        // an operation against a database. Then we'll return this list of them.
        $this->runPending($migrations, $options,$package);

        return $migrations;
    }


    /**
     * Run an array of migrations.
     *
     * @param  array  $migrations
     * @param  array  $options
     * @return void
     */
    public function runPending(array $migrations, array $options = [],$package=null)
    {

        // Next, we will get the next batch number for the migrations so we can insert
        // correct batch number in the database migrations repository when we store
        // each migration's execution. We will also extract a few of the options.
        $batch = $this->repository->getNextBatchNumber($package);

        $pretend = $options['pretend'] ?? false;

        $step = $options['step'] ?? false;


        // Once we have the array of migrations, we will spin through them and run the
        // migrations "up" so the changes are made to the databases. We'll then log
        // that the migration was run so we don't repeat it next time we execute.
        foreach ($migrations as $file) {
            $this->runUp($file, $batch, $pretend,$package);

            if ($step) {
                $batch++;
            }
        }

    }
    /**
     * Run "up" a migration instance.
     *
     * @param  string  $file
     * @param  int  $batch
     * @param  bool  $pretend
     * @return void
     * @noinspection PhpParamsInspection
     */
    protected function runUp($file, $batch, $pretend,$package=null)
    {
        // First we will resolve a "real" instance of the migration class from this
        // migration file name. Once we have the instances we can run the actual
        // command such as "up" or "down", or we can just simulate the action.
        $migration = $this->resolvePath($file);

        $name = $this->getMigrationName($file);

        if ($pretend) {
            $this->pretendToRun($migration, 'up');
            return;
        }

        $this->write(Task::class, $name, fn () => $this->runMigration($migration, 'up'));

        // Once we have run a migrations class, we will log that it was run in this
        // repository so that we don't try to run it next time we do a migration
        // in the application. A migration repository keeps the migrate order.
        $this->repository->log($package, $name, $batch);
    }
}