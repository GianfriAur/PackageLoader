<?php

namespace Gianfriaur\PackageLoader\Service\PackageProviderService;

use Illuminate\Foundation\Application;

interface PackageProviderServiceInterface
{
    /**
     * @param Application $app
     * @param array<string,array> $packages_list
     * @param array $options
     */
    public function __construct(Application $app, array $packages_list, array $options);

    /**
     * This method must load the packages into the application
     * @return void
     */
    public function load(): void;

    /**
     * This method is used to validate the content of the file configured with resource_file, it must return ture or the error description
     * @return bool|string
     */
    function validatePackageList():bool|string;
}