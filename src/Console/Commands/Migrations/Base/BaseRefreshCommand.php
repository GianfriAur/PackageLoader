<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Base;

use Illuminate\Console\ConfirmableTrait;

class BaseRefreshCommand
{
    use ConfirmableTrait;

    protected $name = 'package-loader:migrate:refresh';

    protected $description = 'Reset and re-run all package migrations';

}