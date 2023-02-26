<?php

return[

    /*
    |--------------------------------------------------------------------------
    | Resource File
    |--------------------------------------------------------------------------
    |
    | Defines the file from which to get the packages to be registered
    | in the application
    |
    */

    'resource_file' => base_path('load_packages.json'),

    /*
    |--------------------------------------------------------------------------
    | Package Service Provider
    |--------------------------------------------------------------------------
    |
    | TODO: description
    |
    */
    'package_service_provider' => Gianfriaur\PackageLoader\Service\DefaultPackageProviderService::class
];