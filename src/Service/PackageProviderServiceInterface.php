<?php

namespace Gianfriaur\PackageLoader\Service;

use Illuminate\Foundation\Application;

interface PackageProviderServiceInterface
{
    /**
     * @param Application $app
     * @param array<string,array{
     *     env:string,
     *     only_debug:bool,
     *     debug:bool,
     *     vendor:string,
     *     namespace:string
     * }> $packages_list
     */
    public function __construct(Application $app, array $packages_list);

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