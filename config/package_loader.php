<?php

return[

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
    | Packages List Loader
    |--------------------------------------------------------------------------
    |
    | Define here which Packages List Loader to use
    |
    */
    'loader' => 'json_file',

    /*
    |--------------------------------------------------------------------------
    | Packages List Loader List
    |--------------------------------------------------------------------------
    |
    | Here all possible Packages List Loader are defined with their default options
    |
    */
    'package_list_loaders'=>[
        'json_file'=>[
            'class'=> \Gianfriaur\PackageLoader\Service\PackagesListLoaderService\JsonFilePackagesListLoaderService::class,
            'options'=>[
                'resource_file' => base_path('packages.json')
            ]
        ]
    ],

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
            'class'=> \Gianfriaur\PackageLoader\Service\PackageProviderService\DefaultPackageProviderService::class,
            'options'=>[]
        ],
        'composer'=>[
            'class'=> \Gianfriaur\PackageLoader\Service\PackageProviderService\ComposerPackageProviderService::class,
            'options'=>[
                // ex: in Custom package the provider is named CustomPackageProvider
                'suffix'=>'PackageProvider',
                // ex: in Custom package the provider is under psr-4/PackageProvider/CustomPackageProvider
                'namespace'=>'PackageProvider'
            ]
        ]
    ]
];