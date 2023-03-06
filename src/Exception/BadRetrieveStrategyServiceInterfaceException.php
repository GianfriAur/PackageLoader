<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\RetrieveStrategyService\RetrieveStrategyServiceInterface;
use Throwable;

class BadRetrieveStrategyServiceInterfaceException extends PackageLoaderException
{
    public function __construct(string $providedRetrieveStrategyService = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "The supplied RetrieveStrategyService ($providedRetrieveStrategyService) does not implement " . RetrieveStrategyServiceInterface::class,
            $code,
            $previous
        );
    }
}