<?php

namespace Gianfriaur\PackageLoader\PackageProvider;

interface PackageWithLocalizationInterface
{
    public function getTranslationPath(): string;
    public function getTranslationNamespace(): string;
}
