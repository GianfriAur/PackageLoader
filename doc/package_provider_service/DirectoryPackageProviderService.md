# `DirectoryPackageProviderService` a PackageProviderService

## Class

`Gianfriaur\PackageLoader\Service\PackageProviderService\DirectoryPackageProviderService`

## Alias: `composer`

### Description

This PackageProvider service allows you to load packages by specifying
directly a specific namespace of your project, eg: a folder

### Package Configuration

| Nome             | Tipo   | Descrizione                                                               |
| ---------------- | ------ |---------------------------------------------------------------------------|
| enabled          | bool   | if true load the package otherwise skip it                                |
| env              | string | define on which env to load the package, use 'ALL' for everyone           |
| only_debug       | bool   | if true it will be loaded only if the application is in debug mode        |
| debug            | bool   | if true it will load the package in debug mode independently from laravel |
| package_provider | string | name with the namespace of the PackageProvider class                      |

### Options

| Nome             | Tipo    | Descrizione                                                                                                                                                                            |
|------------------|---------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| suffix           | string  | represents the package class suffix, ex: in Custom package the provider is named Custom[ `suffix` ]                                                                                    |
| namespace_prefix | string  | allows you to define the location in the namespace of the PackageProvider ex: in Custom package the provider is under [ `namespace_prefix` ]/Custom/[ `namespace` ]/Custom[ `suffix` ] |
| namespace        | string  | allows you to define the base namespace where all packages start                                                                                                                       |

### Standard definition of this PackageProvider Service

```PHP
[
    // .....
    'package_service_providers'=>[
        // .....
        'directory'=>[
            'class'=> \Gianfriaur\PackageLoader\Service\PackageProviderService\DirectoryPackageProviderService::class,
            'options'=>[
                // ex: in Custom package the provider is under [ namespace_prefix ]/Custom/[  namespace  ]/Custom[    suffix   ]
                //                                             [ namespace_prefix ]/Custom/[  namespace  ]/CustomPackageProvider
                'suffix'=>'PackageProvider',
                // ex: in Custom package the provider is under [ namespace_prefix ]/Custom/[  namespace  ]/Custom[    suffix   ]
                //                                                         Packages/Custom/[  namespace  ]/Custom[    suffix   ]
                'namespace_prefix'=>'Packages',
                // ex: in Custom package the provider is under [ namespace_prefix ]/Custom/[  namespace  ]/Custom[    suffix   ]
                //                                             [ namespace_prefix ]/Custom/PackageProvider/Custom[    suffix   ]
                'namespace'=>'PackageProvider'

                // At the end the class of PackageProvider is  Packages/Custom/PackageProvider/CustomPackageProvider
            ]
        ],
        // .....
    ],
    // .....
]
```
