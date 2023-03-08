# PackageLoader Documentation


## Functionality

- [X] Parallel migration
- [X] Package Retrieve 
- [X] Package Provider
- [X] Localization
  - [ ] [TODO] Production Compress Localization Command  
- [X] Configuration
  - [ ] [TODO] Production Compress Configuration Command

## Configuration

Read full documentation [Config Documentation](./config/default_config.md)

## Components

- [X] RetrieveStrategyService (to write the documentation) 
  - defines how to retrieve information of packages to download
- [X] [PackageProviderService](./package_provider_service/PackageProviderService.md)
  - Defines how to load packages into the application
- [X] MigrationStrategy (to write the documentation)
  - Defines the strategy with which migrations are made
- [X] TranslationStrategy ( to write the documentation)
  - Defines the strategy with which translations are loaded
- [X] ConfigurationStrategy ( to write the documentation)
  - Defines the strategy with which configuration are loaded 