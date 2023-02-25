<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\ServiceProvider\PackageLoaderServiceProvider;
use Throwable;

class BadPackageListException extends PackageLoaderException
{
    public function __construct(string $error, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Some error occurred on '".config(PackageLoaderServiceProvider::CONFIG_NAMESPACE .'.resource_file')."': ".$error, $code, $previous);
    }
}