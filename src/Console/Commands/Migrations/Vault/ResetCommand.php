<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Vault;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseResetCommand;
use Gianfriaur\PackageLoader\Migration\PackageMigrator;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class ResetCommand extends BaseResetCommand
{

    protected PackageMigrator $migrator;

    protected PackageProviderServiceInterface $packageProviderService;

    protected array $packages = [];

    public function __construct(
        PackageMigrator $migrator,
        PackageProviderServiceInterface $packageProviderService)
    {
        parent::__construct();
        $this->migrator = $migrator;
        $this->packageProviderService= $packageProviderService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $packages = $this->option('package');

        if (count($packages)===0) {
            $packages = array_keys($this->packageProviderService->getPackageProviders());
        }

        $this->packages = $packages;

        if (! $this->confirmToProceed()) {
            return 1;
        }

        return $this->migrator->usingConnection($this->option('database'), function () {
            // First, we'll make sure that the migration table actually exists before we
            // start trying to rollback and re-run all of the migrations. If it's not
            // present we'll just bail out with an info message for the developers.
            if (! $this->migrator->repositoryExists()) {
                 $this->components->warn('Migration table not found.');
                return;
            }

            foreach ( $this->packages as $package) {

                $this->migrator->setOutput($this->output)->resetPackage(
                    $package,  $this->getMigrationPaths($package), $this->option('pretend')
                );
            }


        });
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['package', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The package look'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production'],
            ['path', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The path(s) to the migrations files to be executed'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run'],
        ];
    }

}