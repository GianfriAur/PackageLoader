<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Base;

use Gianfriaur\PackageLoader\PackageProvider\PackageWithMigrationsInterface;
use Illuminate\Console\Command;

class BaseCommand extends Command
{

    protected function getMigrationPaths(string $package): array
    {

        $packageProvider = $this->packageProviderService->getPackageProvider($package);

        if ($packageProvider instanceof PackageWithMigrationsInterface){
            return $packageProvider->getMigrationPaths();
        }

        return [];
    }

}