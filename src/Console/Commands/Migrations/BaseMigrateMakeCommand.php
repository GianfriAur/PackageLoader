<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations;

class BaseMigrateMakeCommand extends BaseCommand
{
    protected $signature = 'package-loader:migrate:make {package : the domain of designated package} {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}';

    protected $description = 'Create a new package migration file';
}