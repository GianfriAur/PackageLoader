<?php

namespace Gianfriaur\PackageLoader;

use Gianfriaur\PackageLoader\ServiceProvider\ServicesProvider;
use Illuminate\Support\ServiceProvider;

class PackageLoaderServiceProvider extends ServiceProvider
{
    const CONFIG_NAMESPACE = "package_loader";
    const CONFIG_FILE_NANE = "package_loader.php";

    public function boot(): void
    {
        $this->bootConfig();
    }


    public function register(): void
    {
        $this->registerConfig();

        $this->app->register(ServicesProvider::class);
    }

    private function bootConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/' . self::CONFIG_FILE_NANE => config_path(self::CONFIG_FILE_NANE),
        ]);
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/' . self::CONFIG_FILE_NANE, self::CONFIG_NAMESPACE
        );
    }

}