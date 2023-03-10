<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Base;

use Illuminate\Console\Command;

class BaseInstallCommand extends Command
{
    protected $name = 'package-loader:migrate:install';

    protected $description = 'Create the package migration repository';
}