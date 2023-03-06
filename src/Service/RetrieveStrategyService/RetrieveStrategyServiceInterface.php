<?php

namespace Gianfriaur\PackageLoader\Service\RetrieveStrategyService;

use Illuminate\Foundation\Application;

interface RetrieveStrategyServiceInterface
{
    /**
     * @param Application $app
     * @param array $options
     */
    public function __construct(Application $app, array $options);

    public function exceptionBaseMessage(): string;

    public function getPackagesList(): array;

    public function updatePackage(string $name, array $package_detail);


}