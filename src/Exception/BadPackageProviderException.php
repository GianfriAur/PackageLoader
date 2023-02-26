<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\PackageProvider\AbstractPackageProvider;
use Gianfriaur\PackageLoader\ServiceProvider\PackageLoaderServiceProvider;
use Throwable;

class BadPackageProviderException extends PackageLoaderException
{
    public function __construct(string $class, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "$class not extend ".AbstractPackageProvider::class,
            $code,
            $previous
        );
    }
}