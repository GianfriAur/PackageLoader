# PackageLoader Documentation


## Functionality

- [X] `[OK]` Parallel migration
- [X] `[OK]` Package Retrieve 
- [X] `[OK]` Package Provider
- [ ] `[TODO]` Gestione delle translation

## Configuration

Read full documentation [Config Documentation](./config/default_config.md)

## Components

- [X] RetrieveStrategyService (to write the documentation) 
  - defines how to retrieve information of packages to download
- [X] [PackageProviderService](./package_provider_service/PackageProviderService.md)
  - Defines how to load packages into the application
- [X] MigrationStrategy (to write the documentation)
  - Defines the strategy with which migrations are made
- [ ] TranslationStrategy (to implement | to write the documentation)
  - Defines the strategy with which translations are loaded
  