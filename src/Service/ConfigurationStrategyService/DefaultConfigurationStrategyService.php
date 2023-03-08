<?php

namespace Gianfriaur\PackageLoader\Service\ConfigurationStrategyService;

use Gianfriaur\PackageLoader\PackageProvider\PackageWithConfigurationInterface;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Foundation\Application;

readonly class DefaultConfigurationStrategyService implements ConfigurationStrategyServiceInterface
{

    public function __construct(private Application $app, private PackageProviderServiceInterface $packageProviderService, private array $options)
    {
    }

    public function registerConfigurations(): void
    {
        if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');

            foreach ( $this->packageProviderService->getPackageProviders() as $package_name => $packageProvider){
                if ($packageProvider instanceof PackageWithConfigurationInterface){

                    $config->set($packageProvider->getConfigurationNamespace(), array_merge(
                        require $packageProvider->getConfigurationFilePath(), $config->get($packageProvider->getConfigurationNamespace(), [])
                    ));

                }
            }

        }
    }
}