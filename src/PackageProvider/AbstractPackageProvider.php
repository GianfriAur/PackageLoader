<?php

namespace Gianfriaur\PackageLoader\PackageProvider;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

abstract class AbstractPackageProvider extends ServiceProvider
{
    public function __construct($app, protected readonly PackageProviderServiceInterface $packageServiceProvider,protected readonly bool $debug) {
        parent::__construct($app);

        $this->migration_paths = (new Collection($this->migration_paths))
            ->map(fn ($e)=>realpath($e))
            ->toArray();
    }

    protected array $migration_paths=[];

    public function getMigrationPaths(): array
    {
        return $this->migration_paths;
    }
}