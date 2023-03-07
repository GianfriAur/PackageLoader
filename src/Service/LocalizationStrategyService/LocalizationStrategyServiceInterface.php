<?php

namespace Gianfriaur\PackageLoader\Service\LocalizationStrategyService;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Foundation\Application;

interface LocalizationStrategyServiceInterface
{
    public function __construct(Application $app, PackageProviderServiceInterface $packageProviderService, array $options);

    public function registerLocalizationOnResolving():void;
}