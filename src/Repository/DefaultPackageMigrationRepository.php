<?php

namespace Gianfriaur\PackageLoader\Repository;

use Illuminate\Database\Migrations\MigrationRepositoryInterface;

/**
 * This strategy uses the native laravel one with a bridge
 * between package.migration.repository  migration.repository
 */
readonly class DefaultPackageMigrationRepository implements PackageMigrationRepositoryInterface
{

    public function __construct(private MigrationRepositoryInterface $migrationRepository)
    {
    }

    public function getRan(string $package): array
    {
        return $this->migrationRepository->getRan();
    }

    public function getMigrations(string $package, int $steps): array
    {
        return $this->migrationRepository->getMigrations($steps);
    }

    public function getLast(string $package): array
    {
        return $this->migrationRepository->getLast();
    }

    public function getMigrationBatches(string $package): array
    {
        return $this->migrationRepository->getMigrationBatches();
    }

    public function log(string $package, string $file, int $batch): void
    {
        $this->migrationRepository->log($file, $batch);
    }

    public function delete(string $package, object $migration): void
    {
        $this->migrationRepository->delete($migration);
    }

    public function getNextBatchNumber(string $package): int
    {
        return $this->migrationRepository->getNextBatchNumber();
    }

    public function createRepository(): void
    {
        $this->migrationRepository->createRepository();
    }

    public function repositoryExists(): bool
    {
       return $this->migrationRepository->repositoryExists();
    }

    public function deleteRepository(): void
    {
        $this->migrationRepository->deleteRepository();
    }

    public function setSource(?string $name): void
    {
        $this->migrationRepository->setSource($name);
    }
}