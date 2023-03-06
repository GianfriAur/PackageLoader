# How to create a PackageProviderService

Your new PackagesListLoaderService must implement the interface

`Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface`

es:

```PHP
<?php

namespace MY_NAMESPACE;


use Gianfriaur\PackageLoader\Exception\BadPackageListException;
use Illuminate\Foundation\Application;

class CustomPackageProviderService implements PackageProviderServiceInterface
{

    public function __construct(protected readonly Application $app, protected readonly array $packages_list, protected readonly array $options)
    {
    }

    public function validatePackageList(): bool|string
    {
        // Returns true if the PackagesListLoaderService has provided all the necessary data
        // Otherwise it returns a string describing the error
    }

    public function load(): void
    {
        // It is the function responsible for executing $this->app->register( /* instance of the PackageProvider */ );
        // This function in missing PackageProvider should throw MissingPackageProviderException
        // Also if the class which should be a PackageProvider does not extend AbstractPackageProvider it should throw BadPackageProviderException
    }

    function getPackageProviders(): array
    {
        // Returns a list of PackageProvider instances in the following format
        // [
        //     'PackageName' => /* instance of PackageProvider */
        // ]
    }

    function getPackageProvider(string $name): AbstractPackageProvider
    {
        // This function returns the specific instance of PackageProvider my name
        // If it doesn't find the package it should throw Package ProviderNotFoundException

        // Keep in mind that this feature could be implemented like this
        //if (!array_key_exists($name, $this->packages_cache_list)) {
        //    throw new PackageProviderNotFoundException($name, array_keys($this->packages_cache_list));
        //}
        //return $this->packages_cache_list[$name];
    }
}
```

## Registration and use of our `CustomPackageProviderService`

Once our PackageProvider Service has been created, we need to load it into
`config/package_loader.php` in the option `package_list_loaders`

```PHP
'package_service_providers'=>[
    // ...
    'my_custom_name'=>[
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

    'provider' => 'my_custom_name',

    // ....
```
