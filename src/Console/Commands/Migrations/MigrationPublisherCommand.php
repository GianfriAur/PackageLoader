<?php

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations;

use Gianfriaur\PackageLoader\Migration\PackageMigrator;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;

class MigrationPublisherCommand extends Command
{
    use ConfirmableTrait;

    protected $name = 'package-loader:migrate:publish';

    protected $description = 'Publish package migration in main application migration directory';


    protected array $packages = [];

    public function __construct(
        private readonly PackageProviderServiceInterface $packageProviderService)
    {
        parent::__construct();
    }

    public function handle()
    {

        $app_migration_path  = base_path('database/migrations/');

        $override = $this->option('override');

        $packages = $this->option('package');

        if (count($packages) === 0) {
            $packages = array_keys($this->packageProviderService->getPackageProviders());
        }

        $this->packages = $packages;

        if (!$this->confirmToProceed()) {
            return 1;
        }

        $this->components->info('Publishing for packages [<fg=green>' . join('</> ,<fg=green>', $packages) . '</>]');

        $allMigrations = [];
        foreach ($this->packages as $package) {
            $allMigrations[$package] = [];

            $packageProvider = $this->packageProviderService->getPackageProvider($package);
            foreach ($packageProvider->getMigrationPaths() as $path) {
                $filesInFolder = File::allFiles($path);
                foreach($filesInFolder as $key => $p){

                    $files = pathinfo($p);

                    $allMigrations[$package][$files['basename']] = $files['dirname'];
                }
            }
        }
        $this->components->twoColumnDetail('<fg=gray>Package - Migration name</>', '<fg=gray>Output directory - </><fg=gray> </><fg=gray> </><fg=gray>Action</>');
        foreach ($allMigrations as $package => $migrations) {
            foreach ($migrations as $basename => $dirname) {
                $exists = file_exists($app_migration_path.$basename);
                if (!$exists || $override){
                    File::copy($dirname.'/'.$basename,$app_migration_path.$basename);

                    $this->components->twoColumnDetail(
                        '<fg=green>' . $package . '</> - ' . $basename,
                        $app_migration_path.$basename. ' - ' .
                        ($exists ? '<fg=yellow>OVERRIDE</>' : '<fg=gray> </><fg=gray> </><fg=green> WRITE</>' )
                    );
                }else{
                    $this->components->twoColumnDetail(
                        '<fg=green>' . $package . '</> - ' . $basename,
                        $app_migration_path.$basename. ' - <fg=gray> SKIPPED</>'
                    );
                }
            }
        }
        return 0;
    }

    protected function getOptions()
    {
        return [
            ['package', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The package look'],
            ['override', null, InputOption::VALUE_NONE, 'Force the override of existing migration'],
        ];
    }
}