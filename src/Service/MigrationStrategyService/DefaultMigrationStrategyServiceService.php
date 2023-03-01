<?php

namespace Gianfriaur\PackageLoader\Service\MigrationStrategyService;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseInstallCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseMigrateCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseMigrateMakeCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseResetCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseRollbackCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseStatusCommand;
use Gianfriaur\PackageLoader\Repository\DefaultPackageMigrationRepository;
use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Application;

readonly class DefaultMigrationStrategyServiceService implements MigrationStrategyServiceInterface
{

    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(private Application $app, private array $options)
    {
    }

    public function getMigrationRepository(): PackageMigrationRepositoryInterface|null
    {
        return null;
    }

    public function getMigrator(): Migrator|null
    {
        return null;
    }

    public function getCreator(): MigrationCreator|null
    {
        return null;
    }

    public function getInstallCommand(): BaseInstallCommand|null
    {
        return null;
    }

    public function getStatusCommand(): BaseStatusCommand|null
    {
        return null;
    }

    public function getMigrateMakeCommand():BaseMigrateMakeCommand|null
    {
        return null;
    }

    public function getMigrateCommand(): BaseMigrateCommand|null
    {
        return null;
    }

    public function getRollbackCommand(): BaseRollbackCommand|null
    {
       return null;
    }

    public function getResetCommand(): BaseResetCommand|null
    {
        return null;
    }
}