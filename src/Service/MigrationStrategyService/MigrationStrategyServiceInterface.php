<?php

namespace Gianfriaur\PackageLoader\Service\MigrationStrategyService;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseFreshCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseInstallCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseMigrateCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseMigrateMakeCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseRefreshCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseResetCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseRollbackCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseStatusCommand;
use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Application;

interface MigrationStrategyServiceInterface
{
    /**
     * @param Application $app
     * @param array $options
     */
    public function __construct(Application $app, array $options);

    /**
     * return new PackageMigrationRepositoryInterface if your strategy has a custom migration repository
     * return null if your strategy hasn't any migration repository
     */
    public function getMigrationRepository(): PackageMigrationRepositoryInterface|null;

    /**
     * return new Migrator if your strategy has a custom migrator
     * return null if your strategy hasn't any migrator
     */
    public function getMigrator(): Migrator|null;

    /**
     * return new MigrationCreator if your strategy has a custom migration creator
     * return null if your strategy hasn't any migration creator
     */
    public function getCreator(): MigrationCreator|null;

    /**
     * return new BaseInstallCommand if your strategy has a custom migration migrate command
     * return null if your strategy hasn't any migration migrate command
     */
    public function getMigrateCommand(): BaseMigrateCommand|null;

    /**
     * return new BaseInstallCommand if your strategy has a custom migration migrate command
     * return null if your strategy hasn't any migration migrate command
     */
    public function getFreshCommand():BaseFreshCommand|null;

    /**
     * return new BaseInstallCommand if your strategy has a custom migration install command
     * return null if your strategy hasn't any migration install command
     */
    public function getInstallCommand(): BaseInstallCommand|null;

    /**
     * return new BaseRefreshCommand if your strategy has a custom migration fresh command
     * return null if your strategy hasn't any migration fresh command
     */
    public function getRefreshCommand(): BaseRefreshCommand | null;

    /**
     * return new BaseResetCommand if your strategy has a custom migration reset command
     * return null if your strategy hasn't any migration reset command
     */
    public function getResetCommand(): BaseResetCommand|null;

    /**
     * return new BaseRollbackCommand if your strategy has a custom migration rollback command
     * return null if your strategy hasn't any migration rollback command
     */
    public function getRollbackCommand(): BaseRollbackCommand|null;

    /**
     * return new BaseInstallCommand if your strategy has a custom migration status command
     * return null if your strategy hasn't any migration status command
     */
    public function getStatusCommand(): BaseStatusCommand|null;

    /**
     * return new BaseMigrateMakeCommand if your strategy has a custom migration make command
     * return null if your strategy hasn't any migration make command
     */
    public function getMigrateMakeCommand(): BaseMigrateMakeCommand|null;
}