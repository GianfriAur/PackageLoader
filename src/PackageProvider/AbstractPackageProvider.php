<?php

namespace Gianfriaur\PackageLoader\PackageProvider;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Support\ServiceProvider;

abstract class AbstractPackageProvider extends ServiceProvider
{
    public function __construct($app, protected readonly PackageProviderServiceInterface $packageServiceProvider,protected readonly bool $debug) {
        parent::__construct($app);
    }

    protected array $migration_paths=[];

    public function getMigrationPaths(): array
    {
        return $this->migration_paths;
    }
}