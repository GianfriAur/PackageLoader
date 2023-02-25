<?php

namespace Gianfriaur\PackageLoader\Service;

use Gianfriaur\PackageLoader\Exception\MissingPackageProviderException;
use Gianfriaur\PackageLoader\PackageProvider\AbstractPackageProvider;
use Illuminate\Foundation\Application;

/**
 * Default PackageProviderService
 *
 * If you don't intend to create specific packages type for your project use this PackageProviderService
 * This PackageProvider Service provides the following single package configuration:
 * {
 *      "Name": {
 *          "env":"string",
 *          "only_debug": bool,
 *          "debug": bool,
 *          "vendor": "string",
 *          "namespace": "string"
 *      },
 *      ....
 * }
 */
class PackageProviderService implements PackageProviderServiceInterface
{
    /**
     * @param Application $app
     * @param array<string,array{
     *     env:string,
     *     only_debug:bool,
     *     debug:bool,
     *     vendor:string,
     *     namespace:string
     * }> $packages_list
     */
    public function __construct(protected readonly Application $app, protected readonly array $packages_list) { }

    public function validatePackageList(): bool|string
    {
        foreach ($this->packages_list as $package_name => $package_name_config) {
            $valid_name = preg_replace('/[^A-Za-z0-9]/', '', str_replace(' ', '', $package_name));

            if ( $package_name !== $valid_name ) return "Package name $package_name mismatch with role [^A-Za-z0-9] expected: $valid_name ";       // assert package_name have only A-Za-z0-9
            if ( !array_key_exists('env', $package_name_config) ) return "Missing 'env' parameter on $package_name configuration";                         // assert env exist
            if ( !array_key_exists('only_debug', $package_name_config) ) return "Missing 'only_debug' parameter on $package_name configuration";           // assert only_debug exist
            if ( !array_key_exists('debug', $package_name_config) ) return "Missing 'debug' parameter on $package_name configuration";                     // assert debug exist
            if ( !array_key_exists('vendor', $package_name_config) ) return "Missing 'vendor' parameter on $package_name configuration";                   // assert vendor exist
            // TODO: namespace optional if vendor/**/**/composer.json contain a ./src psr-4 ROLE
            if ( !array_key_exists('namespace', $package_name_config) ) return "Missing 'namespace' parameter on $package_name configuration";             // assert namespace exist

        }
        return true;
    }

    public function load():void
    {
        $env   = config('app.env');
        $debug = config('app.debug');

        foreach ($this->packages_list as $package_name => $package_name_config) {
            if ( $package_name_config[ 'env' ] === 'ALL' || $env === $package_name_config[ 'env' ] ) {
                if ( $package_name_config[ 'only_debug' ] === false || ($package_name_config[ 'only_debug' ] === $debug && $debug === true) ) {
                    $package_provider_class= $package_name_config['namespace'].'\\PackageProvider\\'.$package_name.'PackageProvider';
                    if (!class_exists($package_provider_class)){
                        throw new MissingPackageProviderException($package_provider_class,$package_name_config['vendor'] );
                    }

                    /** @var AbstractPackageProvider $package_provider */
                    $package_provider = new $package_provider_class($this->app,$this,$package_name_config['debug'] );

                    $this->app->register($package_provider);

                }
            }
        }
    }
}