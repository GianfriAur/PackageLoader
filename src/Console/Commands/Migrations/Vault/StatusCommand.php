<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Vault;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\BaseStatusCommand;
use Gianfriaur\PackageLoader\Migration\PackageMigrator;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;

class StatusCommand extends BaseStatusCommand
{
    protected PackageMigrator $migrator;

    protected PackageProviderServiceInterface $packageProviderService;

    public function __construct(PackageMigrator $migrator, PackageProviderServiceInterface $packageProviderService)
    {
        parent::__construct();
        $this->migrator = $migrator;
        $this->packageProviderService = $packageProviderService;
    }

    public function handle()
    {
        return $this->migrator->usingConnection($this->option('database'), function () {
            if ( !$this->migrator->repositoryExists() ) {
                $this->components->error('Package Migration table not found.');

                return 1;
            }
            $packages = $this->option('package');



            if (count($packages)===0) {
                $packages = array_keys($this->packageProviderService->getPackageProviders());
            }

            $this->components->info('Migrations for packages [<fg=green>' . join('</> ,<fg=green>',$packages) . '</>]');

            //prepare migrations

            $migrations = new Collection();

            foreach ($packages as $package) {
                $ran = $this->migrator->getRepository()->getRan($package);
                $batches = $this->migrator->getRepository()->getMigrationBatches($package);
                $migrations = $migrations->merge($this->getStatusFor($ran, $batches, $package));
            }
            if (count($migrations)>0){
                $this->components->twoColumnDetail('<fg=gray>Package - Migration name</>', '<fg=gray>Batch / Status</>');

                $migrations->each(
                    fn($migration) => $this->components->twoColumnDetail('<fg=green>' . $migration[ 2 ] . '</> - ' . $migration[ 0 ], $migration[ 1 ])
                );

                $this->newLine();
            }else{
                $this->components->info('No migrations found');
            }
            return 0;
        });
    }

    protected function getStatusFor(array $ran, array $batches, string $package)
    {
        return Collection::make($this->getAllMigrationFiles($package))
            ->map(function ($migration) use ($ran, $batches,$package) {
                $migrationName = $this->migrator->getMigrationName($migration);

                $status = in_array($migrationName, $ran)
                    ? '<fg=green;options=bold>Ran</>'
                    : '<fg=yellow;options=bold>Pending</>';

                if ( in_array($migrationName, $ran) ) {
                    $status = '[' . $batches[ $migrationName ] . '] ' . $status;
                }

                return [$migrationName, $status, $package];
            });
    }

    protected function getAllMigrationFiles(string $package): array
    {
        return $this->migrator->getMigrationFiles($this->getMigrationPaths($package));
    }

    protected function getOptions(): array
    {
        return [
            ['package', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The package look'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use']
        ];
    }
}