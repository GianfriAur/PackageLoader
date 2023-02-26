<?php

namespace Gianfriaur\PackageLoader\Service\PackagesListLoaderService;


use Gianfriaur\PackageLoader\Exception\BadPackageListException;
use Gianfriaur\PackageLoader\Exception\MissingPackagesListLoaderServiceOptionException;
use Gianfriaur\PackageLoader\ServiceProvider\PackageLoaderServiceProvider;
use Illuminate\Foundation\Application;

class JsonFilePackagesListLoaderService implements PackagesListLoaderServiceInterface
{

    private array $package_list;

    public function __construct(protected readonly Application $app, protected readonly array $options)
    {
    }

    public function exceptionBaseMessage(): string
    {
        return "Some error occurred on '" . $this->getOption('resource_file') . "': ";
    }

    private function getOption($name): mixed
    {
        if (!array_key_exists($name, $this->options)) {
            throw new MissingPackagesListLoaderServiceOptionException($name, $this);
        }
        return $this->options[$name];
    }


    public function getPackagesList(): array
    {
        if (!isset($this->package_list)) {

            $resource_file = $this->getOption('resource_file');

            if (!file_exists($resource_file)) {
                throw new BadPackageListException('File missing');
            }

            $this->package_list = json_decode(file_get_contents($resource_file), true);
        }

        return $this->package_list;

    }

    private function persistPackageList()
    {
        $resource_file = $this->getOption('resource_file');
        if (!file_exists($resource_file)) {
            throw new BadPackageListException('File missing');
        }

        file_put_contents($resource_file, json_encode($this->package_list, JSON_PRETTY_PRINT));
    }

    public function updatePackage(string $name, array $package_detail)
    {
        $this->package_list[$name] = $package_detail;
        $this->persistPackageList();
    }


}