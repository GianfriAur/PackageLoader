<?php /** @noinspection DuplicatedCode */

namespace Gianfriaur\PackageLoader\Console\Commands;

use Gianfriaur\PackageLoader\Service\RetrieveStrategyService\RetrieveStrategyServiceInterface;
use Illuminate\Console\Command;

class ListPackageCommand extends Command
{
    protected $signature = 'package-loader:list';

    protected $description = 'This command display all packages names';

    public function handle(RetrieveStrategyServiceInterface $packagesRetrieveService): void
    {
        $packages_names = array_keys($packagesRetrieveService->getPackagesList());

        $this->info("Packages: [ " .implode(', ',$packages_names). " ]");
    }
}