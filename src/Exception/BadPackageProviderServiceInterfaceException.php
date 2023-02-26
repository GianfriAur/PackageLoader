<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Throwable;

class BadPackageProviderServiceInterfaceException extends PackageLoaderException
{
    public function __construct(string $providedPackageLoaderService = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct(
            "The supplied PackageProviderService ($providedPackageLoaderService) does not implement ".PackageProviderServiceInterface::class,
            $code,
            $previous
        );
    }
}