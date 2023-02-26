<?php

namespace Gianfriaur\PackageLoader\Service\MigrationStrategyService;

use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Illuminate\Foundation\Application;

interface MigrationStrategyServiceInterface
{
    /**
     * @param Application $app
     * @param array $options
     */
    public function __construct(Application $app, array $options);

    public function getMigrationRepository():PackageMigrationRepositoryInterface;
}