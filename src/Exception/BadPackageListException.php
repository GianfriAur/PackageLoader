<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\PackagesListLoaderService\PackagesListLoaderServiceInterface;
use Throwable;

class BadPackageListException extends PackageLoaderException
{
    public function __construct(string $error, int $code = 0, ?Throwable $previous = null)
    {
        $message = app()->get(PackagesListLoaderServiceInterface::class)->exceptionBaseMessage();

        parent::__construct(
            $message . $error,
            $code,
            $previous
        );
    }
}