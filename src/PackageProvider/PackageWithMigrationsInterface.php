<?php

namespace Gianfriaur\PackageLoader\PackageProvider;

interface PackageWithMigrationsInterface
{
    public function getMigrationPaths():array;
}
