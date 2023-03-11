# PackageLoader Documentation


## Functionality

- [X] Parallel migration
- [X] Package Retrieve 
- [X] Package Provider
- [X] Localization
  - [ ] `NEXT RELEASE 1.2 :`  Production Compress Localization Command  
- [X] Configuration
  - [ ] `NEXT RELEASE 1.3 :` Production Compress Configuration Command

## Configuration

Read full documentation [Config Documentation](./config/default_config.md)

## Components

- [X] RetrieveStrategyService (to write the documentation) 
  - defines how to retrieve information of packages to download
- [X] [PackageProviderService](./package_provider_service/PackageProviderService.md)
  - Defines how to load packages into the application
- [X] MigrationStrategy (to write the documentation)
  - Defines the strategy with which migrations are made
- [X] [TranslationStrategy](./localization_strategy_service/LocalizationStrategyService.md)
  - Defines the strategy with which translations are loaded
- [X] [ConfigurationStrategy](./configuration_strategy_service/ConfigurationStrategyService.md)
  - Defines the strategy with which configuration are loaded 