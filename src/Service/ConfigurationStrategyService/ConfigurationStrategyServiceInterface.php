<?php

namespace Gianfriaur\PackageLoader\Service\ConfigurationStrategyService;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Foundation\Application;

interface ConfigurationStrategyServiceInterface
{
    public function __construct(Application $app, PackageProviderServiceInterface $packageProviderService, array $options);

    public function registerConfigurations():void;
}