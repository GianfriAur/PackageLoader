# `DefaultConfigurationStrategyService` a ConfigurationStrategyService

## Class

`Gianfriaur\PackageLoader\Service\ConfigurationStrategyService\DefaultConfigurationStrategyService`

## Alias: `default`

### Description

This ConfigurationStrategy Service allows you to load the packages configuration at same moment 

### Options


This ConfigurationStrategy Service has no configuration parameters

### Standard definition of this ConfigurationStrategy Service

```PHP
[
    // .....
    'configuration_strategies' => [
        // .....
        'default' => [
            'class' => \Gianfriaur\PackageLoader\Service\ConfigurationStrategyService\DefaultConfigurationStrategyService::class,
            'options' => []
        ],
        // .....
    ],
    // .....
]
```
