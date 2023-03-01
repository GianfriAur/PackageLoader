<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Vault;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseRollbackCommand;
use Gianfriaur\PackageLoader\Migration\PackageMigrator;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Symfony\Component\Console\Input\InputOption;

class RollbackCommand extends BaseRollbackCommand
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
        foreach ( $this->packages as $package) {

            $this->migrator->usingConnection($this->option('database'), function () use($package){
                $this->migrator->setOutput($this->output)->rollback(
                    $this->getMigrationPaths($package), [
                        'pretend' => $this->option('pretend'),
                        'step' => (int) $this->option('step'),
                        'package' => $package,
                    ]
                );
            });

        }


        return 0;
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
            ['step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted'],
        ];
    }

}