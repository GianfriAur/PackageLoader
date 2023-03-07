<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Retrieve Strategy
    |--------------------------------------------------------------------------
    |
    | Define here which Retrieve Strategy to use
    |
    */
    'retrieve_strategy' => 'json_file',

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
    | Localization Strategy
    |--------------------------------------------------------------------------
    |
    | Define here which Localization Strategy to use
    |
    */
    'localization_strategy' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Migration Strategy
    |--------------------------------------------------------------------------
    |
    | Use 'default' for using a default laravel migration
    | Use 'vault' for managing package parallel migrations
    | Use null to not load migration management, in this case will
    |     be loaded a command named package-loader:migrate:publish
    |     this command will take care of publishing the migrations
    |     of all the packages in the main application
    |
    */
    'migration_strategy' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Packages List Loader List
    |--------------------------------------------------------------------------
    |
    | Here all possible Retrieve Strategy are defined with their default options
    |
    */
    'retrieve_strategies' => [
        'json_file' => [
            'class' => \Gianfriaur\PackageLoader\Service\RetrieveStrategyService\JsonFileRetrieveStrategyService::class,
            'options' => [
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
    'package_service_providers' => [
        'default' => [
            'class' => \Gianfriaur\PackageLoader\Service\PackageProviderService\DefaultPackageProviderService::class,
            'options' => []
        ],
        'composer' => [
            'class' => \Gianfriaur\PackageLoader\Service\PackageProviderService\ComposerPackageProviderService::class,
            'options' => [
                // ex: in Custom package the provider is named CustomPackageProvider
                'suffix' => 'PackageProvider',
                // ex: in Custom package the provider is under [ composer psr-4 ]/PackageProvider/Custom[ suffix ]
                'namespace' => 'PackageProvider'
            ]
        ],
        'directory' => [
            'class' => \Gianfriaur\PackageLoader\Service\PackageProviderService\DirectoryPackageProviderService::class,
            'options' => [
                // ex: in Custom package the provider is under [ namespace_prefix ]/Custom/[  namespace  ]/Custom[    suffix   ]
                //                                             [ namespace_prefix ]/Custom/[  namespace  ]/CustomPackageProvider
                'suffix' => 'PackageProvider',
                // ex: in Custom package the provider is under [ namespace_prefix ]/Custom/[  namespace  ]/Custom[    suffix   ]
                //                                                         Packages/Custom/[  namespace  ]/Custom[    suffix   ]
                'namespace_prefix' => 'Packages',
                // ex: in Custom package the provider is under [ namespace_prefix ]/Custom/[  namespace  ]/Custom[    suffix   ]
                //                                             [ namespace_prefix ]/Custom/PackageProvider/Custom[    suffix   ]
                'namespace' => 'PackageProvider'

                // At the end the class of PackageProvider is  Packages/Custom/PackageProvider/CustomPackageProvider
            ]
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Localization Strategies List
    |--------------------------------------------------------------------------
    |
    | Here all possible Localization Strategy are defined with their options
    |
    */
    'localization_strategies' => [
        'default' => [
            'class' => \Gianfriaur\PackageLoader\Service\LocalizationStrategyService\DirectoryLocalizationStrategyService::class,
            'options' => []
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Strategy List
    |--------------------------------------------------------------------------
    |
    | Here all possible Migration Strategy are defined with their options
    |
    */
    'migration_strategies' => [
        'default' => [
            'class' => \Gianfriaur\PackageLoader\Service\MigrationStrategyService\DefaultMigrationStrategyServiceService::class,
            'options' => []
        ],
        'vault' => [
            'class' => \Gianfriaur\PackageLoader\Service\MigrationStrategyService\VaultMigrationStrategyServiceService::class,
            'options' => [
                'table' => 'packages_migrations'
            ]
        ],
    ]
];
