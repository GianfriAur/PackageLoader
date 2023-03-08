<?php

namespace Gianfriaur\PackageLoader\Service\LocalizationStrategyService;

use Gianfriaur\PackageLoader\PackageProvider\PackageWithLocalizationInterface;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Foundation\Application;
use Illuminate\Translation\Translator;

readonly class DefaultLocalizationStrategyService implements LocalizationStrategyServiceInterface
{

    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(private Application $app, private PackageProviderServiceInterface $packageProviderService, private array $options)
    {
    }

    // make sure in every condition the callback was called also after already resolved
    protected function callAfterResolving($name, $callback)
    {
        $this->app->afterResolving($name, $callback);

        if ($this->app->resolved($name)) {
            $callback($this->app->make($name), $this->app);
        }
    }


    public function registerLocalizationOnResolving():void {
        $this->callAfterResolving('translator', function (Translator $translator){
            foreach ( $this->packageProviderService->getPackageProviders() as $package_name => $packageProvider){
                if ($packageProvider instanceof PackageWithLocalizationInterface){
                    $translator->addNamespace($packageProvider->getTranslationNamespace(), $packageProvider->getTranslationPath());
                }
           }
        });
    }

}