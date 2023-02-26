<?php /** @noinspection DuplicatedCode */

namespace Gianfriaur\PackageLoader\Console\Commands;

use App\Models\User;
use Gianfriaur\PackageLoader\Service\PackagesListLoaderService\PackagesListLoaderServiceInterface;
use Illuminate\Console\Command;

class DisablePackageCommand extends Command
{
    protected $signature = 'package-loader:disable {package-name}';

    protected $description = 'This command enables a passed package';

    /**
     * Execute the console command.
     */
    public function handle(PackagesListLoaderServiceInterface $packagesListLoaderService): void
    {
        $package_name = $this->input->getArgument('package-name');
        $all_package_names = array_keys($packagesListLoaderService->getPackagesList()) ?? [];
        if (!in_array($package_name, $all_package_names)) {
            $this->error($package_name . ' not found il list: [' . implode(', ', $all_package_names) . ']');
        }

        $package_config = $packagesListLoaderService->getPackagesList()[$package_name];
        $package_config['load'] = false;

        $packagesListLoaderService->updatePackage($package_name, $package_config);

        $this->info("$package_name Package Disabled!");
    }
}