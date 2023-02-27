<?php

namespace Gianfriaur\PackageLoader\Exception;

use Throwable;

class PackageProviderNotFoundException extends PackageLoaderException
{
    public function __construct(string $not_find = "", array $packages_names = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "Package named $not_find  was not found" . (sizeof($packages_names) > 0 ? ' in [' . implode(', ', $packages_names) . ']' : ''),
            $code,
            $previous
        );
    }
}