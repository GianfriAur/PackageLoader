<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\RetrieveStrategyService\RetrieveStrategyServiceInterface;
use Gianfriaur\PackageLoader\PackageLoaderServiceProvider;
use Throwable;

class MissingRetrieveStrategyServiceOptionException extends PackageLoaderException
{
    public function __construct(string $option, RetrieveStrategyServiceInterface $packagesRetrieveService, int $code = 0, ?Throwable $previous = null)
    {
        $packages_retrieve_service_class = $packagesRetrieveService::class;
        parent::__construct(
            "For $packages_retrieve_service_class missing $option option in config/" . PackageLoaderServiceProvider::CONFIG_FILE_NANE,
            $code,
            $previous
        );
    }
}