<?php

namespace Gianfriaur\PackageLoader\Exception;

use Throwable;

class MissingPackageProviderException extends PackageLoaderException
{
    public function __construct(string $expected_class = "", string $vendor = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "Missing PackageProvider: $expected_class" . ($vendor !== "" ? " in vendor: $vendor " : ""),
            $code,
            $previous
        );
    }
}