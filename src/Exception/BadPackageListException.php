<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\Service\RetrieveStrategyService\RetrieveStrategyServiceInterface;
use Throwable;

class BadPackageListException extends PackageLoaderException
{
    public function __construct(string $error, int $code = 0, ?Throwable $previous = null)
    {
        $message = app()->get(RetrieveStrategyServiceInterface::class)->exceptionBaseMessage();

        parent::__construct(
            $message . $error,
            $code,
            $previous
        );
    }
}