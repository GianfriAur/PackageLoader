# LocalizationStrategyService

This type of service implements the interface
`Gianfriaur\PackageLoader\Service\LocalizationStrategyService\LocalizationStrategyServiceInterface`

They are used to record the translations of your packages

## Why use LocalizationStrategy

Since PackageProvider extends ServiceProvider we can wonder why it has been given the possibility
to use a strategy specifically to load translations instead of using directly `$this->loadTranslationsFrom($path,$namespace);`

There is a very simple answer to this legitimate question, if for each PackageProvider it is necessary
to individually register a callback to the `afterResolving` and check if the 'translator' service has
been resolved and then load the translations, this as the packages increase can cause a drop in performance.

Yes it is true of just ~50 milliseconds for 100 packets

With the `DefaultLocalizationStrategy` Service this is done only once leaving only the time to read the files

But hey, there is, using it costs you nothing, but you save some time in requests


## LocalizationStrategyService Provided

- ## [DefaultLocalizationStrategyService](./DefaultLocalizationStrategyService.md)
    - load translations from folder
    - loads the translations in one go

## Custom LocalizationStrategyService

If you have special needs you can always create your own strategy to extract the settings

Read this Tutorial
[How to create a LocalizationStrategyService](./CustomLocalizationStrategyService.md)