<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Base;

use Illuminate\Console\Command;

class BaseCommand extends Command
{

    protected function getMigrationPaths(string $package): array
    {
        return $this->packageProviderService->getPackageProvider($package)->getMigrationPaths();
    }

}