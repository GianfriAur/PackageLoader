<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\RetrieveStrategyService\RetrieveStrategyServiceInterface;
use Throwable;

class BadPackagesListLoaderServiceInterfaceException extends PackageLoaderException
{
    public function __construct(string $providedPackagesListLoaderService = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "The supplied PackagesListLoaderService ($providedPackagesListLoaderService) does not implement " . RetrieveStrategyServiceInterface::class,
            $code,
            $previous
        );
    }
}