<?php

return[

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
    | Package Service Provider
    |--------------------------------------------------------------------------
    |
    | Define here which Package Service Provider to use
    |
    */
    'provider' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Migration Strategy
    |--------------------------------------------------------------------------
    |
    | Use 'default' for using a default laravel migration
    | Use 'vault' for managing package parallel migrations
    | Use null to not load migration management
    |
    */
    'migration_strategy' => 'vault',

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
                // ex: in Custom package the provider is under [ composer psr-4 ]/PackageProvider/Custom[ suffix ]
                'namespace'=>'PackageProvider'
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Strategy List
    |--------------------------------------------------------------------------
    |
    | Here all possible Migration Strategy are defined with their options
    |
    */
    'migration_strategies'=>[
        'default' =>[
            'class'=> \Gianfriaur\PackageLoader\Service\MigrationStrategyService\DefaultMigrationStrategyServiceService::class,
            'options'=>[]
        ],
        'vault' =>[
            'class'=> \Gianfriaur\PackageLoader\Service\MigrationStrategyService\VaultMigrationStrategyServiceService::class,
            'options'=>[
                'table'=>'packages_migrations'
            ]
        ],
    ]
];