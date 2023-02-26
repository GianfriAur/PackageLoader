<?php

namespace Gianfriaur\PackageLoader\Service\MigrationStrategyService;

use Gianfriaur\PackageLoader\Repository\DefaultPackageMigrationRepository;
use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Illuminate\Foundation\Application;

readonly class DefaultMigrationStrategyServiceService implements MigrationStrategyServiceInterface
{

    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(private Application $app, private array $options)
    {
    }

    public function getMigrationRepository(): PackageMigrationRepositoryInterface
    {
        return new DefaultPackageMigrationRepository($this->app->get('migration.repository') );
    }
}