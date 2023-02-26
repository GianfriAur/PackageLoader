<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\MigrationStrategyService\MigrationStrategyServiceInterface;

use Throwable;

class BadMigrationStrategyServiceInterfaceException extends PackageLoaderException
{
    public function __construct(string $providedMigrationStrategyService = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct(
            "The supplied MigrationStrategyService ($providedMigrationStrategyService) does not implement ".MigrationStrategyServiceInterface::class,
            $code,
            $previous
        );
    }
}