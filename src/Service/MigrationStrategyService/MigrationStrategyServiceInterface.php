<?php

namespace Gianfriaur\PackageLoader\Service\MigrationStrategyService;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseInstallCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseMigrateCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseMigrateMakeCommand;
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


    public function getMigrateCommand(): BaseMigrateCommand|null;
    //TODO: public function getFreshCommand():null;

    /**
     * return new BaseInstallCommand if your strategy has a custom migration install command
     * return null if your strategy hasn't any migration install command
     * @return BaseInstallCommand|null
     */
    public function getInstallCommand(): BaseInstallCommand|null;

    //TODO: public function getRefreshCommand():null;
    //TODO: public function getResetCommand():null;
    public function getRollbackCommand(): BaseRollbackCommand|null;

    /**
     * return new BaseInstallCommand if your strategy has a custom migration status command
     * return null if your strategy hasn't any migration status command
     * @return BaseStatusCommand|null
     */
    public function getStatusCommand(): BaseStatusCommand|null;


    public function getMigrateMakeCommand(): BaseMigrateMakeCommand|null;
}