<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Base;

use Illuminate\Console\ConfirmableTrait;

class BaseFreshCommand
{
    use ConfirmableTrait;

    protected $name = 'package-loader:migrate:fresh';

    protected $description = 'Drop all tables and re-run all package migrations';

}