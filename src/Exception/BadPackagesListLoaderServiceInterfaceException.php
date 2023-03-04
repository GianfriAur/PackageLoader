<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\PackagesListLoaderService\PackagesListLoaderServiceInterface;
use Throwable;

class BadPackagesListLoaderServiceInterfaceException extends PackageLoaderException
{
    public function __construct(string $providedPackagesListLoaderService = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "The supplied PackagesListLoaderService ($providedPackagesListLoaderService) does not implement " . PackagesListLoaderServiceInterface::class,
            $code,
            $previous
        );
    }
}