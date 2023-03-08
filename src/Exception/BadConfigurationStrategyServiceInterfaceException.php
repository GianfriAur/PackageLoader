<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\ConfigurationStrategyService\ConfigurationStrategyServiceInterface;
use Throwable;

class BadConfigurationStrategyServiceInterfaceException extends PackageLoaderException
{
    public function __construct(string $providedConfigurationStrategyService = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "The supplied ConfigurationStrategyService ($providedConfigurationStrategyService) does not implement " . ConfigurationStrategyServiceInterface::class,
            $code,
            $previous
        );
    }
}