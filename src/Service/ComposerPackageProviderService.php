<?php /** @noinspection DuplicatedCode */

namespace Gianfriaur\PackageLoader\Service;

use Gianfriaur\PackageLoader\Exception\BadPackageListException;
use Gianfriaur\PackageLoader\Exception\MissingPackageProviderException;
use Gianfriaur\PackageLoader\PackageProvider\AbstractPackageProvider;
use Illuminate\Foundation\Application;

/**
 * Composer PackageProviderService
 *
 * Use this PackageProviderService if you are going to not define the location of the PackageProviders,
 * this service will load them
 *
 * This PackageProvider Service provides the following single package configuration:
 * {
 *      "Name": {
 *          "env":"string",
 *          "only_debug": bool,
 *          "debug": bool,
 *          "vendor": "string",
 *      },
 *      ....
 * }
 */
readonly class ComposerPackageProviderService implements PackageProviderServiceInterface
{
    /**
     * @param Application $app
     * @param array<string,array{
     *     env:string,
     *     only_debug:bool,
     *     debug:bool,
     *     vendor:string,
     * }> $packages_list
     */
    public function __construct(protected Application $app, protected array $packages_list) { }

    public function validatePackageList(): bool|string
    {
        foreach ($this->packages_list as $package_name => $package_name_config) {
            $valid_name = preg_replace('/[^A-Za-z0-9]/', '', str_replace(' ', '', $package_name));

            if ( $package_name !== $valid_name ) return "Package name $package_name mismatch with role [^A-Za-z0-9] expected: $valid_name ";               // assert package_name have only A-Za-z0-9
            if ( !array_key_exists('env', $package_name_config) ) return "Missing 'env' parameter on $package_name configuration";                         // assert env exist
            if ( !array_key_exists('only_debug', $package_name_config) ) return "Missing 'only_debug' parameter on $package_name configuration";           // assert only_debug exist
            if ( !array_key_exists('debug', $package_name_config) ) return "Missing 'debug' parameter on $package_name configuration";                     // assert debug exist
            if ( !array_key_exists('vendor', $package_name_config) ) return "Missing 'vendor' parameter on $package_name configuration";                   // assert vendor exist
        }
        return true;
    }

    private function generateClassName(string $namespace,string $package_name):string{
        return str_replace('\\\\','\\', $namespace . '\\PackageProvider\\' . $package_name . 'PackageProvider');
    }

    public function load(): void
    {
        $env   = config('app.env');
        $debug = config('app.debug');

        foreach ($this->packages_list as $package_name => $package_name_config) {
            if ( $package_name_config[ 'env' ] === 'ALL' || $env === $package_name_config[ 'env' ] ) {
                if ( $package_name_config[ 'only_debug' ] === false || ($package_name_config[ 'only_debug' ] === $debug && $debug === true) ) {
                    $composer_file = base_path('vendor/' . $package_name_config[ 'vendor' ] . '/composer.json');

                    if ( !file_exists($composer_file) ) {
                        throw new BadPackageListException("missing composer.json in $composer_file for package $package_name");
                    }
                    $composer_file = json_decode(file_get_contents($composer_file), true);

                    if ( !array_key_exists('autoload', $composer_file) || !array_key_exists('psr-4', $composer_file[ 'autoload' ]) ) {
                        throw new BadPackageListException("missing psr-4 autoload in $composer_file for package $package_name");
                    }

                    $psr4s = $composer_file[ 'autoload' ][ 'psr-4' ];

                    $candidates = [];

                    foreach ($psr4s as $namespace => $folder){
                        $package_provider_class = $this->generateClassName($namespace,$package_name);
                        if (class_exists($package_provider_class)){
                            $candidates[]= $package_provider_class;
                        }
                    }

                    if (sizeof($candidates)===0){
                        throw new MissingPackageProviderException($this->generateClassName(array_key_first($psr4s),$package_name) , $package_name_config[ 'vendor' ]);
                    }elseif (sizeof($candidates)>1){
                        throw new BadPackageListException("$package_name  has too many PackageProvider");
                    }

                    $package_provider_class = $candidates[0];

                    /** @var AbstractPackageProvider $package_provider */
                    $package_provider = new $package_provider_class($this->app, $this, $package_name_config[ 'debug' ]);

                    $this->app->register($package_provider);

                }
            }
        }
    }
}