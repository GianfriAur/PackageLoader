<?php

namespace Gianfriaur\PackageLoader\PackageProvider;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

abstract class AbstractPackageProvider extends ServiceProvider
{
    public function __construct($app, protected readonly PackageProviderServiceInterface $packageServiceProvider, protected readonly bool $debug)
    {
        parent::__construct($app);

        // patch all migration_paths
        $this->migration_paths = (new Collection($this->migration_paths))
            ->map(fn($e) => realpath($e))
            ->toArray();

        // path translation_path
        if ($this->translation_path) {
            $this->translation_path =  realpath($this->translation_path);
        }
    }

    protected array $migration_paths = [];
    protected ?string $translation_path = null;


    public function getMigrationPaths(): array
    {
        return $this->migration_paths;
    }

    public function getTranslationPath(): ?string
    {
        return $this->translation_path;
    }
}