<?php

namespace Gianfriaur\PackageLoader\Repository;

interface PackageMigrationRepositoryInterface
{
    /**
     * Get the completed migrations.
     */
    public function getRan(string $package):array;

    /**
     * Get the list of migrations.
     */
    public function getMigrations(string $package, int $steps):array;

    /**
     * Get the last migration batch.
     */
    public function getLast(string $package):array;

    /**
     * Get the completed migrations with their batch numbers.
     */
    public function getMigrationBatches(string $package):array;

    /**
     * Log that a migration was run.
     */
    public function log(string $package,string $file, int $batch):void;

    /**
     * Remove a migration from the log.
     */
    public function delete(string $package,object $migration):void;

    /**
     * Get the next migration batch number.
     */
    public function getNextBatchNumber(string $package):int;

    /**
     * Create the migration repository data store.
     */
    public function createRepository():void;

    /**
     * Determine if the migration repository exists.
     */
    public function repositoryExists():bool;

    /**
     * Delete the migration repository data store.
     */
    public function deleteRepository():void;

    /**
     * Set the information source to gather data.
     */
    public function setSource(?string $name):void;
}