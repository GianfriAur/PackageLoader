# PackageLoader

just another package manager for `laravel` nothing more and nothing less

but if you want to use it you are welcome

## Goal

In an ideal world we will all be the standard,

But it's also true that if there were a standard, our project wouldn't be the only one,
wouldn't it?

Starting from this we can say that it is not always possible to make things according to
the existing standard, or simply for the 'company' logic this is not possible

The purpose of this library is to provide a tool without forcing you to necessarily follow
the rules decided at the start, if you want you can write your own logic if necessary and
expect that nothing will 'break'

Also, because you can't think about what you need before you need it 😉

## Install 

### Require package 
> composer require gianfriaur/package-loader

### Publish configuration file
> php artisan vendor:publish --provider="Gianfriaur\PackageLoader\PackageLoaderServiceProvider"

### First setup

* [TODO:DOC: package in directory + json file retrieve method](#)
* [TODO:DOC: package in vendor + json file retrieve method](#)

### Tips

 * If it is useful you can manage the migrations of your packages in a parallel way from those of laravel [TODO:DOC: configure vault migration_strategy](#)

## Documentation

You can find the full documentation at [this location](./doc/README.md)

## Stress Tests 😍

I have tried to reduce loading times to reasonably low times

obtaining good results

* Load 10 packages : ~2 milliseconds
* Load 100 packages : ~10 milliseconds
* Load 1000 packages : ~400 milliseconds

you can find the stress test in this test: `tests/Stress/StressTest.php`

## Why use LocalizationStrategy

Since PackageProvider extends ServiceProvider we can wonder why it has been given the possibility 
to use a strategy specifically to load translations instead of using directly `$this->loadTranslationsFrom($path,$namespace);`

There is a very simple answer to this legitimate question, if for each PackageProvider it is necessary 
to individually register a callback to the `afterResolving` and check if the 'translator' service has 
been resolved and then load the translations, this as the packages increase can cause a drop in performance.

Yes it is true of just ~50 milliseconds for 100 packets

With the `DefaultLocalizationStrategy` Service this is done only once leaving only the time to read the files

But hey, there is, using it costs you nothing, but you save some time in requests

> In the future there will be a command that will be loaded by the strategy that will allow you in production to collapse all the translation files of all the packages into a single one so that I could further reduce the times for loading all the translation files

## Next releases
 - ### 1.1.x
   - Configuration
     - New strategy `JsonConfigurationStrategyService`
     - New strategy `JollyConfigurationStrategyService`
   - New Features
     - New kind of strategy `ViewStrategyService`
   
 - ### 1.2.x
   - Localization
     - Production Compress Localization Command  
   - Configuration
     - New strategy `DatabaseConfigurationStrategyService`
     
- ### 1.3.x
  - Configuration
    - Production Compress Configuration Command