<?php

namespace Gianfriaur\PackageLoader\PackageProvider;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Support\ServiceProvider;

abstract class AbstractPackageProvider extends ServiceProvider
{
    public function __construct($app, protected readonly PackageProviderServiceInterface $packageServiceProvider, bool $debug) {
        parent::__construct($app);
    }
}