# `ComposerPackageProviderService` a PackageProviderService

## Class

`Gianfriaur\PackageLoader\Service\PackageProviderService\ComposerPackageProviderService`

## Alias: `composer`

### Description

This PackageProvider Service allows you to load packages by specifying
directly in the name of the vendor

### Package Configuration

| Nome             | Tipo   | Descrizione                                                               |
|------------------|--------|---------------------------------------------------------------------------|
| enabled          | bool   | if true load the package otherwise skip it                                |
| env              | string | define on which env to load the package, use 'ALL' for everyone           |
| only_debug       | bool   | if true it will be loaded only if the application is in debug mode        |
| debug            | bool   | if true it will load the package in debug mode independently from laravel |
| package_provider | string | name with the namespace of the PackageProvider class                      |

### Options

| Nome      | Tipo   | Descrizione                                                                                                                                                                   |
| --------- | ------ |-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| suffix    | string | represents the package class suffix, ex: in Custom package the provider is named Custom[ `suffix` ]                                                                           |
| namespace | string | allows you to define the location in the namespace of the PackageProvider ex: in Custom package the provider is under [ `composer psr-4` ]/[ `namespace` ]/Custom[ `suffix` ] |

### Common mistakes

If there are multiple psr-4s Composer and more than one of them returns a class
valid representing a PackageProvider will be thrown an exception of
promiscuity

### Standard definition of this PackageProvider Service

```PHP
[
    // .....
    'package_service_providers'=>[
        // .....
        'composer'=>[
            'class'=> \Gianfriaur\PackageLoader\Service\PackageProviderService\ComposerPackageProviderService::class,
            'options'=>[
                // ex: in Custom package the provider is named CustomPackageProvider
                'suffix'=>'PackageProvider',
                // ex: in Custom package the provider is under [ composer psr-4 ]/PackageProvider/Custom[ suffix ]
                'namespace'=>'PackageProvider'
            ]
        ],
        // .....
    ],
    // .....
]
```
