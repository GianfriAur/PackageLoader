# PackageProviderService

This type of service implements the interface
`Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface`


It is used to implement the effective registration in the application of the packages

## PackageProviderService Provided

- ## [DefaultPackageProviderService](./DefaultPackageProviderService.md)

  - Load packages directly from the PackageProvider class reference
  - It supports splitting in env
  - Support debug mode (both laravel and parallel)
  - Supports a maximum of one PackageProvider per package

- ## [ComposerPackageProviderService](./ComposerPackageProviderService.md)

  - Load packages directly from composer, automatically defining how to reach the class
  - It supports splitting in env
  - Support debug mode (both laravel and parallel)
  - Supports a maximum of one PackageProvider per package

- ## [DirectoryPackageProviderService](./DirectoryPackageProviderService.md)

  - Load packages directly from a namespace of your application
  - It supports splitting in env
  - Support debug mode (both laravel and parallel)
  - Supports a maximum of one PackageProvider per package

## Custom PackageProviderService

If you have different needs, for example, recover them by scanning a folder in
recursively (although for that you should use the
DefaultPackageProviderService with a custom PackagesListLoaderService going to
scan folders deducting packages) or anything you can think of
you can create PackageProviderService

Read this Tutorial
[How to create a PackageProviderService](./CustomPackageProviderService.md)
