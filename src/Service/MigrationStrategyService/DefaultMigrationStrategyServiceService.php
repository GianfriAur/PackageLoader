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
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Application;

readonly class DefaultMigrationStrategyServiceService implements MigrationStrategyServiceInterface
{

    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(private Application $app, private array $options)
    {
        // wait until Migrator was loaded
        $this->app->resolving(Migrator::class, function (Migrator $migrator, $app) {
            /** @var PackageProviderServiceInterface $package_service_provider */
            $package_service_provider = $this->app->get('package_loader.package_service_provider');

            // register to default migrator the package_service_provider database paths
            foreach ($package_service_provider->getPackageProviders() as $package_service_provider){
                foreach (  $package_service_provider->getMigrationPaths() as $path){
                    $migrator->path($path);
                }
            }
        });
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

    public function getFreshCommand(): BaseFreshCommand|null
    {
        return null;
    }

    public function getRefreshCommand(): BaseRefreshCommand|null
    {
        return null;
    }
}