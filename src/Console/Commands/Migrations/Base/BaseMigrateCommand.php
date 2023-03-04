<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Base;

use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Console\Isolatable;

class BaseMigrateCommand extends BaseCommand implements Isolatable
{
    use ConfirmableTrait;

    protected $signature = 'package-loader:migrate:migrate {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--package=* : packages }
                {--pretend : Dump the SQL queries that would be run}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    protected $description = 'Run the package database migrations';
}