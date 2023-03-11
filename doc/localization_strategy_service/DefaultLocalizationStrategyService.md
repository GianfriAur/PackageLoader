# `DefaultLocalizationStrategyService` a LocalizationStrategyService

## Class

`Gianfriaur\PackageLoader\Service\LocalizationStrategyService\DefaultLocalizationStrategyService`

## Alias: `default`

### Description

This LocalizationStrategy Service allows you to load the packages translation at same moment

### Options

This LocalizationStrategy Service has no configuration parameters

### Standard definition of this LocalizationStrategy Service

```PHP
[
    // .....
    'localization_strategies' => [
        // .....
        'default' => [
            'class' => \Gianfriaur\PackageLoader\Service\LocalizationStrategyService\DefaultLocalizationStrategyService::class,
            'options' => []
        ],
        // .....
    ],
    // .....
]
```
