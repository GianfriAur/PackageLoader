<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Gianfriaur\PackageLoader\PackageLoaderServiceProvider;
use Throwable;

class MissingPackageProviderServiceOptionException extends PackageLoaderException
{
    public function __construct(string $option, PackageProviderServiceInterface $packageProviderService, int $code = 0, ?Throwable $previous = null)
    {
        $package_provider_service_class = $packageProviderService::class;
        parent::__construct(
            "For $package_provider_service_class missing $option option in config/" . PackageLoaderServiceProvider::CONFIG_FILE_NANE,
            $code,
            $previous
        );
    }
}