<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations;

use Illuminate\Console\ConfirmableTrait;

class BaseResetCommand extends BaseCommand
{
    use ConfirmableTrait;

    protected $name = 'package-loader:migrate:reset';

    protected $description = 'Rollback all package migrations';

}