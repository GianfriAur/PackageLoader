<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\PackagesListLoaderService\PackagesListLoaderServiceInterface;
use Gianfriaur\PackageLoader\ServiceProvider\PackageLoaderServiceProvider;
use Throwable;

class MissingPackagesListLoaderServiceOptionException extends PackageLoaderException
{
    public function __construct(string $option, PackagesListLoaderServiceInterface $packagesListLoaderService, int $code = 0, ?Throwable $previous = null)
    {
        $packages_list_loader_service_class = $packagesListLoaderService::class;
        parent::__construct(
            "For $packages_list_loader_service_class missing $option option in config/" . PackageLoaderServiceProvider::CONFIG_FILE_NANE,
            $code,
            $previous
        );
    }
}