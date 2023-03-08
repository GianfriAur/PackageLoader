<?php

namespace Gianfriaur\PackageLoader\PackageProvider;

interface PackageWithConfigurationInterface
{
    public function getConfigurationFilePath(): string;
    public function getConfigurationNamespace(): string;
}
