<?php

namespace Gianfriaur\PackageLoader\Service\MigrationStrategyService;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseInstallCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseStatusCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\Vault\InstallCommand;
use Gianfriaur\PackageLoader\Console\Commands\Migrations\Vault\StatusCommand;
use Gianfriaur\PackageLoader\Exception\MissingMigrationStrategyServiceOptionException;
use Gianfriaur\PackageLoader\Migration\PackageMigrator;
use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Gianfriaur\PackageLoader\Repository\VaultPackageMigrationRepository;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Application;

readonly class VaultMigrationStrategyServiceService implements MigrationStrategyServiceInterface
{

    public function __construct(private Application $app, private array $options)
    {
    }

    /** @noinspection PhpSameParameterValueInspection */
    private function getOption(string $name): mixed
    {
        if (!array_key_exists($name, $this->options)) {
            throw new MissingMigrationStrategyServiceOptionException($name, $this);
        }
        return $this->options[$name];
    }

    public function getMigrationRepository(): PackageMigrationRepositoryInterface
    {
        $db = $this->app->get('db');
        $table = $this->getOption('table');
        return new VaultPackageMigrationRepository($db,$table );
    }

    public function getMigrator(): Migrator|null
    {
        $repository = $this->app->get('package_loader.migration.repository');
        $db = $this->app->get('db');
        $files = $this->app->get('files');
        $events = $this->app->get('events');
        return new PackageMigrator($repository, $db, $files, $events);
    }

    public function getCreator(): MigrationCreator|null
    {
        $files = $this->app->get('files');
        $stubs = $this->app->basePath('stubs');
        return new MigrationCreator($files, $stubs);
    }

    public function getInstallCommand(): BaseInstallCommand|null
    {
        $repository = $this->app->get('package_loader.migration.repository');
        return new InstallCommand($repository);
    }

    public function getStatusCommand(): BaseStatusCommand|null
    {
        $migrator = $this->app->get('package_loader.migrator');
        $package_service_provider = $this->app->get('package_loader.package_service_provider');

        return new StatusCommand($migrator,$package_service_provider);
    }
}