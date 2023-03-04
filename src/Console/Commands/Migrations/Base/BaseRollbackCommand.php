<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Base;

use Illuminate\Console\ConfirmableTrait;

class BaseRollbackCommand extends BaseCommand
{
    use ConfirmableTrait;

    protected $name = 'package-loader:migrate:rollback';

    protected $description = 'Rollback the last database package migration';
}