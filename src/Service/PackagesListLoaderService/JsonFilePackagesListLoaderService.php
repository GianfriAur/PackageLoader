<?php

namespace Gianfriaur\PackageLoader\Service\PackagesListLoaderService;


use Gianfriaur\PackageLoader\Exception\BadPackageListException;
use Gianfriaur\PackageLoader\Exception\MissingPackagesListLoaderServiceOptionException;
use Gianfriaur\PackageLoader\ServiceProvider\PackageLoaderServiceProvider;
use Illuminate\Foundation\Application;

readonly class JsonFilePackagesListLoaderService implements PackagesListLoaderServiceInterface
{

    public function __construct(protected Application $app, protected array $options) { }

    public function exceptionBaseMessage(): string
    {
      return "Some error occurred on '".$this->getOption('resource_file')."': ";
    }

    private function getOption($name): mixed
    {
        if ( !array_key_exists($name, $this->options) ) {
            throw new MissingPackagesListLoaderServiceOptionException($name, $this);
        }
        return $this->options[ $name ];
    }


    public function getPackagesList(): array
    {
        $resource_file = $this->getOption('resource_file');

        if ( !file_exists($resource_file) ) {
            throw new BadPackageListException('File missing');
        }

        return json_decode(file_get_contents($resource_file), true);
    }

    public function updatePackage(string $name, array $package_detail)
    {
        // TODO: Implement updatePackage() method.
    }


}