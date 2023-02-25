<?php

namespace Gianfriaur\PackageLoader\Exception;

use Gianfriaur\PackageLoader\ServiceProvider\PackageLoaderServiceProvider;
use Throwable;

class PackageLoaderMissingConfigException extends PackageLoaderException
{
    public function __construct(string $config_name = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct(
            "PackageLoader cannot load services, $config_name config in file 'config/".PackageLoaderServiceProvider::CONFIG_FILE_NANE.'\'',
            $code, $previous);
    }

}