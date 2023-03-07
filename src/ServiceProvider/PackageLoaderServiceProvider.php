<?php

namespace Gianfriaur\PackageLoader\ServiceProvider;

use Gianfriaur\PackageLoader\Console\Commands\DisablePackageCommand;
use Gianfriaur\PackageLoader\Console\Commands\EnablePackageCommand;
use Gianfriaur\PackageLoader\Console\Commands\ListPackageCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\MigrationPublisherCommand;
use Gianfriaur\PackageLoader\Exception\BadLocalizationStrategyServiceInterfaceException;
use Gianfriaur\PackageLoader\Exception\BadMigrationStrategyServiceInterfaceException;
use Gianfriaur\PackageLoader\Exception\BadRetrieveStrategyServiceException;
use Gianfriaur\PackageLoader\Exception\BadPackageProviderServiceInterfaceException;
use Gianfriaur\PackageLoader\Exception\BadRetrieveStrategyServiceInterfaceException;
use Gianfriaur\PackageLoader\Exception\PackageLoaderMissingConfigException;
use Gianfriaur\PackageLoader\Service\LocalizationStrategyService\LocalizationStrategyServiceInterface;
use Gianfriaur\PackageLoader\Service\MigrationStrategyService\MigrationStrategyServiceInterface;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Gianfriaur\PackageLoader\Service\RetrieveStrategyService\RetrieveStrategyServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PackageLoaderServiceProvider extends ServiceProvider implements DeferrableProvider
{
    const CONFIG_NAMESPACE = "package_loader";
    const CONFIG_FILE_NANE = "package_loader.php";

    protected array $commands = [
        EnablePackageCommand::class,
        DisablePackageCommand::class,
        ListPackageCommand::class
    ];


    public function boot(): void
    {
        $this->bootConfig();
    }

    /**
     * @throws BadRetrieveStrategyServiceException
     * @throws BadRetrieveStrategyServiceInterfaceException
     * @throws BadPackageProviderServiceInterfaceException
     * @throws BadMigrationStrategyServiceInterfaceException
     * @throws BadLocalizationStrategyServiceInterfaceException
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->registerRetrieveStrategyService();

        //register singleton of PackageServiceProviderInterface on alias package_loader.package_service_provider
        $this->registerPackageServiceProvider();
        $this->loadPackageServiceProvider();

        $has_localization_strategy = $this->registerLocalizationStrategyService();
        if ($has_localization_strategy){
            $this->loadLocalizationStrategyService();
        }

        $has_migration_strategy = $this->registerMigrationStrategyService();

        if ($this->app->runningInConsole()) {
            $this->registerCommands();

            if ($has_migration_strategy) {
                $this->registerPackageMigrationServiceProvider();
            } else {
                $this->registerMigrationPublishCommand();
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

    private function registerMigrationPublishCommand()
    {
        $this->commands([MigrationPublisherCommand::class]);
    }

    private function registerCommands()
    {
        $this->commands(array_values($this->commands));
    }

    private function getRetrieveStrategyServiceDefinition(): array
    {
        $retrieve_strategy = $this->getConfig('retrieve_strategy');
        $retrieve_strategies = $this->getConfig('retrieve_strategies');
        return [$retrieve_strategies[$retrieve_strategy]['class'], $retrieve_strategies[$retrieve_strategy]['options']];
    }

    private function getLocalizationStrategyServiceDefinition(): array
    {
        $localization_strategy = $this->getConfig('localization_strategy');
        if ($localization_strategy === null) return [null, []];
        $localization_strategies = $this->getConfig('localization_strategies');
        return [$localization_strategies[$localization_strategy]['class'], $localization_strategies[$localization_strategy]['options']];
    }

    private function getPackageServiceProviderDefinition(): array
    {
        $provider = $this->getConfig('provider');
        $providers = $this->getConfig('package_service_providers');
        return [$providers[$provider]['class'], $providers[$provider]['options']];
    }

    private function getMigrationStrategyServiceDefinition(): array|null
    {
        $migration_strategy = $this->getConfig('migration_strategy', true);
        if ($migration_strategy === null) return [null, []];
        $migration_strategies = $this->getConfig('migration_strategies');
        return [$migration_strategies[$migration_strategy]['class'], $migration_strategies[$migration_strategy]['options']];
    }

    private function registerLocalizationStrategyService(): bool
    {
        [$localization_strategy_service_class, $localization_strategy_service_options] = $this->getLocalizationStrategyServiceDefinition();
        if ($localization_strategy_service_class !== null) {
            if (!is_subclass_of($localization_strategy_service_class, LocalizationStrategyServiceInterface::class)) {
                throw new BadLocalizationStrategyServiceInterfaceException($localization_strategy_service_class);
            }

            // register singleton of retrieve_strategy_service
            $this->app->singleton(LocalizationStrategyServiceInterface::class, function ($app) use ($localization_strategy_service_class, $localization_strategy_service_options) {
                return new $localization_strategy_service_class($app, $this->app->get(PackageProviderServiceInterface::class), $localization_strategy_service_options);
            });
            // add alias of PackagesListLoaderServiceInterface::class on package_loader.retrieve_strategy_service
            $this->app->alias(LocalizationStrategyServiceInterface::class, 'package_loader.localization_strategy_service');
            return true;
        }
        return false;
    }

    private function registerRetrieveStrategyService(): void
    {
        [$retrieve_strategy_service_class, $retrieve_strategy_service_options] = $this->getRetrieveStrategyServiceDefinition();

        if (!is_subclass_of($retrieve_strategy_service_class, RetrieveStrategyServiceInterface::class)) {
            throw new BadRetrieveStrategyServiceInterfaceException($retrieve_strategy_service_class);
        }

        // register singleton of retrieve_strategy_service
        $this->app->singleton(RetrieveStrategyServiceInterface::class, function ($app) use ($retrieve_strategy_service_class, $retrieve_strategy_service_options) {
            return new $retrieve_strategy_service_class($app, $retrieve_strategy_service_options);
        });
        // add alias of PackagesListLoaderServiceInterface::class on package_loader.retrieve_strategy_service
        $this->app->alias(RetrieveStrategyServiceInterface::class, 'package_loader.retrieve_strategy_service');
    }

    private function registerPackageServiceProvider(): void
    {
        [$package_service_provider_class, $package_service_provider_options] = $this->getPackageServiceProviderDefinition();

        if (!is_subclass_of($package_service_provider_class, PackageProviderServiceInterface::class)) {
            throw new BadPackageProviderServiceInterfaceException($package_service_provider_class);
        }

        /** @var RetrieveStrategyServiceInterface $package_list_loader */
        $package_list_loader = $this->app->get(RetrieveStrategyServiceInterface::class);
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


            // register singleton of migration.strategy
            $this->app->singleton(MigrationStrategyServiceInterface::class, function ($app) use ($migration_strategy_service_class, $migration_strategy_service_options) {
                return new $migration_strategy_service_class($app, $migration_strategy_service_options);
            });
            // add alias of PackagesListLoaderServiceInterface::class on package_loader.migration.strategy
            $this->app->alias(MigrationStrategyServiceInterface::class, 'package_loader.migration.strategy');

            return true;
        }
        return false;
    }

    private function registerPackageMigrationServiceProvider(): void
    {
        $this->app->register(PackageMigrationServiceProvider::class);
    }


    private function loadLocalizationStrategyService():void
    {
        /** @var LocalizationStrategyServiceInterface $package_service_provider */
        $package_service_provider = $this->app->get('package_loader.localization_strategy_service');
        $package_service_provider->registerLocalizationOnResolving();
    }

    private function loadPackageServiceProvider(): void
    {
        /** @var PackageProviderServiceInterface $package_service_provider */
        $package_service_provider = $this->app->get('package_loader.package_service_provider');

        if ($error = $package_service_provider->validatePackageList()) {
            if ($error !== true) {
                throw new BadRetrieveStrategyServiceException($error);
            }
        }

        // load all packages
        $package_service_provider->load();

    }

    private function getConfig($name, bool $nullable = false): mixed
    {
        if (!$config = config(self::CONFIG_NAMESPACE . '.' . $name)) {
            if (!$nullable) throw new PackageLoaderMissingConfigException($name);
        }
        return $config;
    }

}