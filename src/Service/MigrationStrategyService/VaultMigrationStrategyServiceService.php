<?php

namespace Gianfriaur\PackageLoader\Service\MigrationStrategyService;

use Gianfriaur\PackageLoader\Exception\MissingMigrationStrategyServiceOptionException;
use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Gianfriaur\PackageLoader\Repository\VaultPackageMigrationRepository;
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
        return new VaultPackageMigrationRepository($this->app->get('db'),$this->getOption('table') );
    }
}