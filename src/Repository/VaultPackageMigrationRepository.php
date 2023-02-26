<?php

namespace Gianfriaur\PackageLoader\Repository;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Query\Builder;

/**
 * This strategy uses the native laravel one with a bridge
 * between package.migration.repository  migration.repository
 */
readonly class VaultPackageMigrationRepository implements PackageMigrationRepositoryInterface
{

    /**
     * The name of the database connection to use.
     */
    protected ?string $connection;

    /**
     * Create a new database migration repository instance.
     */
    public function __construct(private Resolver $resolver, private string $table)
    {
    }

    /**
     * Get the completed migrations.
     */
    public function getRan(string $package): array
    {
        return $this->table()
            ->where('package', '=', $package)
            ->orderBy('batch', 'asc')
            ->orderBy('migration', 'asc')
            ->pluck('migration')->all();
    }

    /**
     * Get the list of migrations.
     */
    public function getMigrations(string $package, int $steps): array
    {
        $query = $this->table()
            ->where('batch', '>=', '1')
            ->where('package', '=', $package);

        return $query->orderBy('batch', 'desc')
            ->orderBy('migration', 'desc')
            ->take($steps)->get()->all();
    }

    /**
     * Get the last migration batch.
     */
    public function getLast(string $package): array
    {
        $query = $this->table()
            ->where('batch', $this->getLastBatchNumber($package))
            ->where('package', '=', $package);

        return $query->orderBy('migration', 'desc')->get()->all();
    }

    /**
     * Get the completed migrations with their batch numbers.
     */
    public function getMigrationBatches(string $package): array
    {
        return $this->table()
            ->where('package', '=', $package)
            ->orderBy('batch', 'asc')
            ->orderBy('migration', 'asc')
            ->pluck('batch', 'migration')->all();
    }

    /**
     * Log that a migration was run.
     */
    public function log(string $package, string $file, int $batch): void
    {
        $record = ['package' => $package, 'migration' => $file, 'batch' => $batch];

        $this->table()->insert($record);
    }

    /**
     * Remove a migration from the log.
     */
    public function delete(string $package, object $migration): void
    {
        $this->table()
            ->where('migration', $migration->migration)
            ->where('package', '=', $package)
            ->delete();
    }

    /**
     * Get the next migration batch number.
     */
    public function getNextBatchNumber(string $package): int
    {
        return $this->getLastBatchNumber($package) + 1;
    }

    /**
     * Get the last migration batch number.
     */
    public function getLastBatchNumber(string $package): int
    {
        return $this->table()
            ->where('package', '=', $package)
            ->max('batch') ?? 0;
    }

    /**
     * Create the migration repository data store.
     */
    public function createRepository(): void
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        $schema->create($this->table, function ($table) {
            $table->increments('id');
            $table->string('package');
            $table->string('migration');
            $table->integer('batch');
        });
    }

    /**
     * Determine if the migration repository exists.
     */
    public function repositoryExists(): bool
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        return $schema->hasTable($this->table);
    }

    /**
     * Delete the migration repository data store.
     */
    public function deleteRepository(): void
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        $schema->drop($this->table);
    }

    /**
     * Get a query builder for the migration table.
     */
    protected function table(): Builder
    {
        return $this->getConnection()->table($this->table)->useWritePdo();
    }

    /**
     * Get the connection resolver instance.
     */
    public function getConnectionResolver(): Resolver
    {
        return $this->resolver;
    }

    /**
     * Resolve the database connection instance.
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->resolver->connection($this->connection);
    }

    /**
     * Set the information source to gather data.
     */
    public function setSource(?string $name): void
    {
        $this->connection = $name;
    }
}