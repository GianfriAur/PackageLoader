<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations;

class BaseStatusCommand extends BaseCommand
{
    protected $name = 'package-loader:migrate:status';

    protected $description = 'Show the status of each migration of packages';
}