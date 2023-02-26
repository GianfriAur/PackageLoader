<?php /** @noinspection DuplicatedCode */

namespace Gianfriaur\PackageLoader\Service\PackageProviderService;

use Gianfriaur\PackageLoader\Exception\BadPackageProviderException;
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
 *          "package_provider": "string"
 *      },
 *      ....
 * }
 */
readonly class DefaultPackageProviderService implements PackageProviderServiceInterface
{
    /**
     * @param Application $app
     * @param array<string,array{
     *     env:string,
     *     only_debug:bool,
     *     debug:bool,
     *     package_provider:string
     * }> $packages_list
     * @param array{} $options
     */
    public function __construct(protected Application $app, protected array $packages_list, protected array $options) { }

    public function validatePackageList(): bool|string
    {
        foreach ($this->packages_list as $package_name => $package_name_config) {
            $valid_name = preg_replace('/[^A-Za-z0-9]/', '', str_replace(' ', '', $package_name));

            if ( $package_name !== $valid_name ) return "Package name $package_name mismatch with role [^A-Za-z0-9] expected: $valid_name ";                             // assert package_name have only A-Za-z0-9
            if ( !array_key_exists('env', $package_name_config) ) return "Missing 'env' parameter on $package_name configuration";                                       // assert env exist
            if ( !array_key_exists('load', $package_name_config) ) return "Missing 'load' parameter on $package_name configuration";                                     // assert load exist
            if ( !array_key_exists('only_debug', $package_name_config) ) return "Missing 'only_debug' parameter on $package_name configuration";                         // assert only_debug exist
            if ( !array_key_exists('debug', $package_name_config) ) return "Missing 'debug' parameter on $package_name configuration";                                   // assert debug exist
            if ( !array_key_exists('package_provider', $package_name_config) ) return "Missing 'package_provider' parameter on $package_name configuration";             // assert package_provider exist

        }
        return true;
    }

    public function load(): void
    {
        $env   = config('app.env');
        $debug = config('app.debug');

        foreach ($this->packages_list as $package_name => $package_name_config) {
            if ( ($package_name_config[ 'env' ] === 'ALL' || $env === $package_name_config[ 'env' ]) && $package_name_config[ 'load' ] ) {
                if ( $package_name_config[ 'only_debug' ] === false || ($package_name_config[ 'only_debug' ] === $debug && $debug === true) ) {
                    $package_provider_class = $package_name_config[ 'package_provider' ];
                    if ( !class_exists($package_provider_class) ) {
                        throw new MissingPackageProviderException($package_provider_class);
                    }

                    if ( !is_subclass_of($package_provider_class, AbstractPackageProvider::class) ) {
                        throw new BadPackageProviderException($package_provider_class);
                    }

                    /** @var AbstractPackageProvider $package_provider */
                    $package_provider = new $package_provider_class($this->app, $this, $package_name_config[ 'debug' ]);


                    $this->app->register($package_provider);

                }
            }
        }
    }
}