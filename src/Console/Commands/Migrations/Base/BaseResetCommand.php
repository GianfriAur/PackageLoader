<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Base;

use Illuminate\Console\ConfirmableTrait;

class BaseResetCommand extends BaseCommand
{
    use ConfirmableTrait;

    protected $name = 'package-loader:migrate:reset';

    protected $description = 'Rollback all package migrations';

}