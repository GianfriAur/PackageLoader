# `DefaultPackageProviderService` a PackageProviderService

## Class

`Gianfriaur\PackageLoader\Service\PackageProviderService\DefaultPackageProviderService`

## Alias: `default`

### Description

This PackageProvider Service allows you to load packages by specifying
directly in the namespace and the class of the PackageProvider

### Package Configuration

| Nome             | Tipo   | Descrizione                                                               |
| ---------------- | ------ |---------------------------------------------------------------------------|
| enabled          | bool   | if true load the package otherwise skip it                                |
| env              | string | define on which env to load the package, use 'ALL' for everyone           |
| only_debug       | bool   | if true it will be loaded only if the application is in debug mode        |
| debug            | bool   | if true it will load the package in debug mode independently from laravel |
| package_provider | string | name with the namespace of the PackageProvider class                      |

### Options


This PackageProvider Service has no configuration parameters

### Standard definition of this PackageProvider Service

```PHP
[
    // .....
    'package_service_providers'=>[
        // .....
        'default'=>[
            'class'=> \Gianfriaur\PackageLoader\Service\PackageProviderService\DefaultPackageProviderService::class,
            'options'=>[]
        ],
        // .....
    ],
    // .....
]
```
