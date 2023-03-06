<?php

namespace Gianfriaur\PackageLoader\Tests\Stress;

use Gianfriaur\PackageLoader\Exception\MissingRetrieveStrategyServiceOptionException;
use Gianfriaur\PackageLoader\Service\RetrieveStrategyService\RetrieveStrategyServiceInterface;
use Illuminate\Foundation\Application;

class FakerRetrieveStrategyService implements RetrieveStrategyServiceInterface
{

    public function __construct(private Application $app, private array $options)
    {
    }

    public function exceptionBaseMessage(): string
    {
        return "Some error occurred on in CustomRetrieveStrategyService : ";
    }

    private function getOption($name): mixed
    {
        if (!array_key_exists($name, $this->options)) {
            throw new MissingRetrieveStrategyServiceOptionException($name, $this);
        }
        return $this->options[$name];
    }


    public function getPackagesList(): array
    {
       return  $this->getOption('faked');
    }

    public function updatePackage(string $name, array $package_detail)
    {
       // DO NOTING
    }
}