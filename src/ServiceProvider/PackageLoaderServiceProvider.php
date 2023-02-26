<?php

namespace Gianfriaur\PackageLoader\ServiceProvider;

use Gianfriaur\PackageLoader\Console\Commands\DisablePackageCommand;
use Gianfriaur\PackageLoader\Console\Commands\EnablePackageCommand;
use Gianfriaur\PackageLoader\Exception\BadMigrationStrategyServiceInterfaceException;
use Gianfriaur\PackageLoader\Exception\BadPackageListException;
use Gianfriaur\PackageLoader\Exception\BadPackageProviderServiceInterfaceException;
use Gianfriaur\PackageLoader\Exception\BadPackagesListLoaderServiceInterfaceException;
use Gianfriaur\PackageLoader\Exception\PackageLoaderMissingConfigException;
use Gianfriaur\PackageLoader\Service\MigrationStrategyService\MigrationStrategyServiceInterface;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Gianfriaur\PackageLoader\Service\PackagesListLoaderService\PackagesListLoaderServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PackageLoaderServiceProvider extends ServiceProvider implements DeferrableProvider
{
    const CONFIG_NAMESPACE = "package_loader";
    const CONFIG_FILE_NANE = "package_loader.php";

    protected array $commands = [
        EnablePackageCommand::class,
        DisablePackageCommand::class
    ];

    protected PackageProviderServiceInterface $packageServiceProvider;

    public function boot(): void
    {
        $this->bootConfig();
    }

    /**
     * @throws BadPackageListException
     * @throws BadPackagesListLoaderServiceInterfaceException
     * @throws BadPackageProviderServiceInterfaceException
     * @throws BadMigrationStrategyServiceInterfaceException
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->registerPackagesListLoader();

        //register singleton of PackageServiceProviderInterface on alias package_loader.package_service_provider
        $this->registerPackageServiceProvider();
        $this->loadPackageServiceProvider();

        $has_migration = $this->registerMigrationStrategyService();

        if ($this->app->runningInConsole()) {
            $this->registerCommands();

            if ($has_migration){
                $this->registerPackageMigrationServiceProvider();
            }
        }

    }

    private function bootConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/' . self::CONFIG_FILE_NANE => config_path(self::CONFIG_FILE_NANE),
        ]);
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/' . self::CONFIG_FILE_NANE, self::CONFIG_NAMESPACE
        );
    }

    private function registerCommands()
    {
        $this->commands(array_values($this->commands));
    }

    private function getPackagesListLoaderServiceDefinition(): array
    {
        $loader = $this->getConfig('loader');
        $loaders = $this->getConfig('package_list_loaders');
        return [$loaders[$loader]['class'], $loaders[$loader]['options']];
    }

    private function getPackageServiceProviderDefinition(): array
    {
        $provider = $this->getConfig('provider');
        $providers = $this->getConfig('package_service_providers');
        return [$providers[$provider]['class'], $providers[$provider]['options']];
    }

    private function getMigrationStrategyServiceDefinition(): array|null
    {
        $migration_strategy = $this->getConfig('migration_strategy');
        if ($migration_strategy === null) return [null, []];
        $migration_strategies = $this->getConfig('migration_strategies');
        return [$migration_strategies[$migration_strategy]['class'], $migration_strategies[$migration_strategy]['options']];
    }

    private function registerPackagesListLoader(): void
    {
        [$packages_list_loader_class, $packages_list_loader_options] = $this->getPackagesListLoaderServiceDefinition();

        if (!is_subclass_of($packages_list_loader_class, PackagesListLoaderServiceInterface::class)) {
            throw new BadPackagesListLoaderServiceInterfaceException($packages_list_loader_class);
        }

        // register singleton of packages_list_loader
        $this->app->singleton(PackagesListLoaderServiceInterface::class, function ($app) use ($packages_list_loader_class, $packages_list_loader_options) {
            return new $packages_list_loader_class($app, $packages_list_loader_options);
        });
        // add alias of PackagesListLoaderServiceInterface::class on package_loader.packages_list_loader
        $this->app->alias(PackagesListLoaderServiceInterface::class, 'package_loader.packages_list_loader');
    }

    private function registerPackageServiceProvider(): void
    {
        [$package_service_provider_class, $package_service_provider_options] = $this->getPackageServiceProviderDefinition();

        if (!is_subclass_of($package_service_provider_class, PackageProviderServiceInterface::class)) {
            throw new BadPackageProviderServiceInterfaceException($package_service_provider_class);
        }

        /** @var PackagesListLoaderServiceInterface $package_list_loader */
        $package_list_loader = $this->app->get(PackagesListLoaderServiceInterface::class);
        // retrieve package_list
        $package_list = $package_list_loader->getPackagesList();

        // register singleton of package_service_provider
        $this->app->singleton(PackageProviderServiceInterface::class, function ($app) use ($package_service_provider_class, $package_list, $package_service_provider_options) {
            return new $package_service_provider_class($app, $package_list, $package_service_provider_options);
        });
        // add alias of $package_service_provider_class::class on package_loader.package_service_provider
        $this->app->alias(PackageProviderServiceInterface::class, 'package_loader.package_service_provider');

    }

    private function registerMigrationStrategyService(): bool
    {
        [$migration_strategy_service_class, $migration_strategy_service_options] = $this->getMigrationStrategyServiceDefinition();

        if ($migration_strategy_service_class !== null) {
            if (!is_subclass_of($migration_strategy_service_class, MigrationStrategyServiceInterface::class)) {
                throw new BadMigrationStrategyServiceInterfaceException($migration_strategy_service_class);
            }


            // register singleton of packages_list_loader
            $this->app->singleton(MigrationStrategyServiceInterface::class, function ($app) use ($migration_strategy_service_class, $migration_strategy_service_options) {
                return new $migration_strategy_service_class($app, $migration_strategy_service_options);
            });
            // add alias of PackagesListLoaderServiceInterface::class on package_loader.packages_list_loader
            $this->app->alias(MigrationStrategyServiceInterface::class, 'package_loader.migration.strategy');

            return true;
        }
        return false;
    }

    private function registerPackageMigrationServiceProvider():void
    {
        $this->app->register( PackageMigrationServiceProvider::class);
    }

    private function loadPackageServiceProvider(): void
    {
        /** @var PackageProviderServiceInterface $package_service_provider */
        $package_service_provider = $this->app->get('package_loader.package_service_provider');

        if ($error = $package_service_provider->validatePackageList()) {
            if ($error !== true) {
                throw new BadPackageListException($error);
            }
        }

        // load all packages
        $package_service_provider->load();

    }

    private function getConfig($name): mixed
    {
        if (!$config = config(self::CONFIG_NAMESPACE . '.' . $name)) {
            throw new PackageLoaderMissingConfigException($name);
        }
        return $config;
    }

}