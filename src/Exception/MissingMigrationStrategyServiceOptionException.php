<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\MigrationStrategyService\MigrationStrategyServiceInterface;
use Gianfriaur\PackageLoader\ServiceProvider\PackageLoaderServiceProvider;
use Throwable;

class MissingMigrationStrategyServiceOptionException extends PackageLoaderException
{
    public function __construct(string $option, MigrationStrategyServiceInterface $migrationStrategyService, int $code = 0, ?Throwable $previous = null)
    {
        $migration_strategy_service_class = $migrationStrategyService::class;
        parent::__construct(
            "For $migration_strategy_service_class missing $option option in config/" . PackageLoaderServiceProvider::CONFIG_FILE_NANE,
            $code,
            $previous
        );
    }
}