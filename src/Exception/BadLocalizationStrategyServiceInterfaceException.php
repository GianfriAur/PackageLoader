<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\LocalizationStrategyService\LocalizationStrategyServiceInterface;
use Throwable;

class BadLocalizationStrategyServiceInterfaceException extends PackageLoaderException
{
    public function __construct(string $providedLocalizationStrategyService = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "The supplied LocalizationStrategyService ($providedLocalizationStrategyService) does not implement " . LocalizationStrategyServiceInterface::class,
            $code,
            $previous
        );
    }
}