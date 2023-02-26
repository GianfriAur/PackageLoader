<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations;

use Gianfriaur\PackageLoader\Repository\PackageMigrationRepositoryInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command
{
    protected $name = 'package-loader:migrate:install';

    protected $description = 'Create the package migration repository';

    public function __construct(private readonly  PackageMigrationRepositoryInterface $repository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle():void
    {
        $this->repository->setSource($this->input->getOption('database'));

        $this->repository->createRepository();

        $this->components->info('Package Migration table created successfully.');
    }

    /**
     * Get the console command options.
     */
    protected function getOptions():array
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],
        ];
    }
}