# ConfigurationStrategyService

This type of service implements the interface
`Gianfriaur\PackageLoader\Service\ConfigurationStrategyService\ConfigurationStrategyServiceInterface`


They are used to record the settings of your packages

## ConfigurationStrategyService Provided

- ## [DefaultConfigurationStrategyService](./DefaultConfigurationStrategyService.md)
    - load settings from a .php file
    - loads the settings in one go
  
- ## `NEXT RELEASE 1.1 :` JsonConfigurationStrategyService
    - load settings from a .json file
    - loads the settings in one go
  
- ## `NEXT RELEASE 1.1:` JollyConfigurationStrategyService
    - load settings from a .php file or .json file
    - loads the settings in one go

- ## `NEXT RELEASE 1.2:` DatabaseConfigurationStrategyService
    - load settings from database
    - loads the settings in one go

## Custom ConfigurationStrategyService

If you have special needs you can always create your own strategy to extract the settings

Read this Tutorial
[How to create a ConfigurationStrategyService](./CustomConfigurationStrategyService.md)
