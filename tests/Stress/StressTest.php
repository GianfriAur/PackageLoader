<?php

namespace Gianfriaur\PackageLoader\Tests\Stress;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Gianfriaur\PackageLoader\ServiceProvider\PackageLoaderServiceProvider;
use Illuminate\Support\Facades\Config;

class StressTest  extends \Orchestra\Testbench\TestCase
{

    /** @test */
    public function test_basic_test()
    {
        $this->assertTrue(true);
    }

    private function makeFakePackage($num){
        $faked = [];
        for ($i = 0; $i!=$num ;$i++ ){
            $name =substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
            eval('namespace Packages\\'.$name.'\PackageProvider;use Gianfriaur\PackageLoader\PackageProvider\AbstractPackageProvider;class '.$name.'PackageProvider extends AbstractPackageProvider {}');
            $faked[$name] = [
                'enabled'=> true,
                'env' => "ALL",
                'only_debug'=>false,
                'debug'=>true,
                'package_provider'=>'Packages\\'.$name.'\\PackageProvider\\'.$name.'PackageProvider'
            ];
        }
        return $faked;
    }


    // want less than 5 milliseconds
    public function test_load_10_package(){

        Config::set('package_loader',[
            'retrieve_strategy'=>'faker',
            'retrieve_strategies'=>[
                'faker'=>[
                    'class'=>  FakerRetrieveStrategyService::class,
                    'options'=>[
                        'faked' => $this->makeFakePackage(10)
                    ]
                ]
            ],
        ]);

        $start = hrtime(true);

        $this->app->register(PackageLoaderServiceProvider::class);

        $duration = ((hrtime(true) - $start) / 1e+6);

        $this->assertLessThanOrEqual(5,$duration);
    }


    // want less than 15 milliseconds
    public function test_load_100_package(){
        Config::set('package_loader',[
            'retrieve_strategy'=>'faker',
            'retrieve_strategies'=>[
                'faker'=>[
                    'class'=>  FakerRetrieveStrategyService::class,
                    'options'=>[
                        'faked' => $this->makeFakePackage(100)
                    ]
                ]
            ],
        ]);

        $start = hrtime(true);

        $this->app->register(PackageLoaderServiceProvider::class);

        $duration = ((hrtime(true) - $start) / 1e+6);

        $this->assertLessThanOrEqual(15,$duration);
    }


    // want less than 500 milliseconds
    public function test_load_1000_package(){
        Config::set('package_loader',[
            'retrieve_strategy'=>'faker',
            'retrieve_strategies'=>[
                'faker'=>[
                    'class'=>  FakerRetrieveStrategyService::class,
                    'options'=>[
                        'faked' => $this->makeFakePackage(1000)
                    ]
                ]
            ],
        ]);

        $start = hrtime(true);

        $this->app->register(PackageLoaderServiceProvider::class);

        $duration = ((hrtime(true) - $start) / 1e+6);

        $this->assertLessThanOrEqual(500,$duration);
    }

}