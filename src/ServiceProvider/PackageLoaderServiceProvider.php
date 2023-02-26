<?php

namespace Gianfriaur\PackageLoader\ServiceProvider;

use Gianfriaur\PackageLoader\Exception\BadPackageListException;
use Gianfriaur\PackageLoader\Exception\BadPackageProviderServiceInterfaceException;
use Gianfriaur\PackageLoader\Exception\PackageLoaderMissingConfigException;
use Gianfriaur\PackageLoader\Service\PackageProviderServiceInterface;
use Illuminate\Support\ServiceProvider;

class PackageLoaderServiceProvider extends ServiceProvider
{
    const CONFIG_NAMESPACE = "package_loader";
    const CONFIG_FILE_NANE = "package_loader.php";

    protected PackageProviderServiceInterface $packageServiceProvider;

    public function boot(): void
    {
        $this->bootConfig();
    }

    public function register(): void
    {
        $this->registerConfig();

        //register singleton of PackageServiceProviderInterface on alias package_loader.package_service_provider
        $this->registerPackageServiceProvider();
        $this->loadPackageServiceProvider();
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


    private function registerPackageServiceProvider(): void
    {
        $package_service_provider_class = $this->getConfig('package_service_provider');

        if ( !is_subclass_of($package_service_provider_class, PackageProviderServiceInterface::class) ) {
            throw new BadPackageProviderServiceInterfaceException($package_service_provider_class);
        }

        // retrieve package List
        $package_list = $this->getLoadPackages();

        // register singleton of package_service_provider
        $this->app->singleton(PackageProviderServiceInterface::class, function ($app) use ($package_service_provider_class, $package_list) {
            return new $package_service_provider_class($app, $package_list);
        });
        // add alias of $package_service_provider_class::class on package_loader.package_service_provider
        $this->app->alias(PackageProviderServiceInterface::class, 'package_loader.package_service_provider');

    }

    private function loadPackageServiceProvider(): void
    {
        /** @var PackageProviderServiceInterface $package_service_provider */
        $package_service_provider = $this->app->get('package_loader.package_service_provider');

        if ( $error = $package_service_provider->validatePackageList() ) {
            if ( $error !== true ) {
                throw new BadPackageListException($error);
            }
        }

        // load all packages
        $package_service_provider->load();

    }

    private function getLoadPackages(): array
    {
        $resource_file = $this->getConfig('resource_file');

        if ( !file_exists($resource_file) ) {
            throw new BadPackageListException('File missing');
        }

        return json_decode(file_get_contents($resource_file), true);
    }

    private function getConfig($name): mixed
    {
        if ( !$config = config(self::CONFIG_NAMESPACE . '.' . $name) ) {
            throw new PackageLoaderMissingConfigException($name);
        }
        return $config;
    }

}