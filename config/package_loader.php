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
    | Define here which Package Service Provider to use
    |
    */
    'provider' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Package Service Providers List
    |--------------------------------------------------------------------------
    |
    | Here all possible Package Service Providers are defined with their options
    |
    */
    'package_service_providers'=> [
        'default'=>[
            'class'=>Gianfriaur\PackageLoader\Service\DefaultPackageProviderService::class,
            'options'=>[]
        ],
        'composer'=>[
            'class'=>Gianfriaur\PackageLoader\Service\ComposerPackageProviderService::class,
            'options'=>[]
        ]
    ]
];