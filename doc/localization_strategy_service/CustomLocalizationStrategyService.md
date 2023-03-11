# How to create a LocalizationStrategyService

Your new LocalizationStrategyService must implement the interface

`Gianfriaur\PackageLoader\Service\LocalizationStrategyService\LocalizationStrategyServiceInterface`

es:

```PHP

namespace MY_NAMESPACE;

use Gianfriaur\PackageLoader\PackageProvider\PackageWithLocalizationInterface;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Foundation\Application;

readonly class CustomLocalizationStrategyService implements LocalizationStrategyServiceInterface
{


    public function __construct(private Application $app, private PackageProviderServiceInterface $packageProviderService, private array $options)
    {
    }

    public function registerLocalizationOnResolving():void {
        // This method should load yhe translations
    }

}

```


## Registration and use of our `CustomLocalizationStrategyService`

Once our CustomLocalizationStrategy Service has been created, we need to load it into
`config/package_loader.php` in the option `localization_strategies`

```PHP
'localization_strategies'=>[
    // ...
    'my_custom_localization_strategy'=>[
        'class'=> \MY_NAMESPACE\CustomLocalizationStrategyService::class,
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

    'localization_strategy' => 'my_custom_localization_strategy',

    // ....
```
