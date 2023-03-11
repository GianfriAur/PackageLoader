# How to create a ConfigurationStrategyService

Your new ConfigurationStrategyService must implement the interface

`Gianfriaur\PackageLoader\Service\ConfigurationStrategyService\ConfigurationStrategyServiceInterface`

es:

```PHP
<?php

namespace MY_NAMESPACE;

use Gianfriaur\PackageLoader\PackageProvider\PackageWithConfigurationInterface;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Foundation\Application;

readonly class CustomConfigurationStrategyService implements ConfigurationStrategyServiceInterface
{

    public function __construct(private Application $app, private PackageProviderServiceInterface $packageProviderService, private array $options)
    {
    }

    public function registerConfigurations(): void
    {
        // This method should load a config
        
        // You can use this structure if help you
    
        //if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
        //    $config = $this->app->make('config');
        //
        //    foreach ( $this->packageProviderService->getPackageProviders() as $package_name => $packageProvider){
        //        if ($packageProvider instanceof PackageWithConfigurationInterface){
        //
        //            Insert here your code
        //    
        //        }
        //    }
        //
        //}
    }
}

```


## Registration and use of our `CustomConfigurationStrategyService`

Once our ConfigurationStrategy Service has been created, we need to load it into
`config/package_loader.php` in the option `configuration_strategies`

```PHP
'configuration_strategies'=>[
    // ...
    'my_custom_configuration_strategy'=>[
        'class'=> \MY_NAMESPACE\CustomPackageProviderService::class,
        'options'=>[
            /* My Opptions */
        ]
    ]
    // ...
],
```

At this point you just have to selected for use that in the file:

`config/package_loader.php` like
```PHP
    // ....

    'configuration_strategy' => 'my_custom_configuration_strategy',

    // ....
```
