<?php /** @noinspection DuplicatedCode */

namespace Gianfriaur\PackageLoader\Console\Commands\Migrations\Vault;

use Gianfriaur\PackageLoader\Console\Commands\Migrations\Base\BaseMigrateMakeCommand;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Database\Console\Migrations\TableGuesser;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class MigrateMakeCommand extends BaseMigrateMakeCommand implements PromptsForMissingInput
{

    protected MigrationCreator $creator;

    protected ?Composer $composer;

    protected PackageProviderServiceInterface $packageProviderService;

    public function __construct(MigrationCreator $creator, ?Composer $composer, PackageProviderServiceInterface $packageProviderService)
    {
        parent::__construct();
        $this->creator = $creator;
        $this->composer = $composer;
        $this->packageProviderService = $packageProviderService;
    }

    public function handle():void
    {
        $package =trim($this->input->getArgument('package'));
        $name = Str::snake(trim($this->input->getArgument('name')));
        $table = $this->input->getOption('table');
        $create = $this->input->getOption('create') ?: false;

        if (! $table && is_string($create)) {
            $table = $create;

            $create = true;
        }
        if (! $table) {
            [$table, $create] = TableGuesser::guess($name);
        }

        $this->writeMigration($name, $table, $create,$package);

      //  $this->composer->dumpAutoloads();
    }

    protected function writeMigration(string $name, string $table, bool $create, string $package)
    {
        $file = $this->creator->create(
            $name, $this->getMigrationPath($package), $table, $create
        );
        $this->components->info(sprintf('Migration [%s] created successfully.', $file));
    }

    protected function getMigrationPath($package): string
    {
        $paths = $this->getMigrationPaths($package);
        return  $this->choice(
            'With migration path?',
            $paths,
            0,
            $maxAttempts = null,
            $allowMultipleSelections = false
        );
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'package' => 'The migration namespace?',
            'name' => 'What should the migration be named?',
        ];
    }
}