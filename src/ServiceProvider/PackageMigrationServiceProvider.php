<?php

namespace Gianfriaur\PackageLoader\ServiceProvider;

use Gianfriaur\PackageLoader\Service\MigrationStrategyService\MigrationStrategyServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class PackageMigrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     */
    protected Collection $commands;

    protected MigrationStrategyServiceInterface $migrationStrategyService;

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->migrationStrategyService = $this->app->get('package_loader.migration.strategy');

        // register Repository, Migrator and Creator if strategy provide it
        $this->registerRepository();
        $this->registerMigrator();
        $this->registerCreator();

        // load Command form strategy
        $this->commands = $this->loadCommands();

        // register all commands
        $this->registerCommands();
    }

    protected function registerRepository(): void
    {
        if ($migrationRepository = $this->migrationStrategyService->getMigrationRepository()) {
            $this->app->singleton('package_loader.migration.repository', function ($app) use ($migrationRepository) {
                return $migrationRepository;
            });
        }
    }

    protected function registerMigrator(): void
    {
        if ($migrator = $this->migrationStrategyService->getMigrator()) {
            $this->app->singleton('package_loader.migrator', function ($app) use ($migrator) {
                return $migrator;
            });
        }
    }

    protected function registerCreator(): void
    {
        if ($creator = $this->migrationStrategyService->getCreator()) {
            $this->app->singleton('package_loader.migration.creator', function ($app) use ($creator) {
                return $creator;
            });
        }
    }

    protected function loadCommands(): Collection
    {
        return (new Collection([
            $this->migrationStrategyService->getMigrateCommand(),
            $this->migrationStrategyService->getFreshCommand(),
            $this->migrationStrategyService->getInstallCommand(),
            $this->migrationStrategyService->getRefreshCommand(),
            $this->migrationStrategyService->getResetCommand(),
            $this->migrationStrategyService->getRollbackCommand(),
            $this->migrationStrategyService->getStatusCommand(),
            $this->migrationStrategyService->getMigrateMakeCommand()
        ]))
            ->filter(fn($e) => $e !== null)
            ->map(fn($e) => [get_class($e), $e]);
    }

    protected function registerCommands(): void
    {
        foreach ($this->commands as [$command_class, $command_instance]) {
            $this->app->singleton($command_class, function ($app) use ($command_instance) {
                return $command_instance;
            });
        }

        $this->commands($this->commands->map(fn($e) => $e[0])->toArray());
    }

    public function provides(): array
    {
        return array_merge([
            'package_loader.migrator', 'package_loader.migration.repository', 'package_loader.migration.creator',
        ], $this->commands->map(fn($e) => $e[0])->toArray());
    }


}