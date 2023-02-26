<?php

namespace Gianfriaur\PackageLoader\Tests\Service;

use Gianfriaur\PackageLoader\Exception\BadPackageProviderException;
use Gianfriaur\PackageLoader\Service\PackageProviderService\DefaultPackageProviderService;

class DefaultPackageProviderServiceTest  extends \Orchestra\Testbench\TestCase
{

    /** @test */
    public function test_basic_test()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function test_new_DefaultPackageProviderService()
    {
        $provider = new  DefaultPackageProviderService($this->app,[],[]);
        $this->assertTrue($provider !== null);
    }

    /** @test */
    public function test_bad_package_list_missing_env()
    {
        $provider = new  DefaultPackageProviderService($this->app,['Test'=>[]],[]);
        $error = $provider->validatePackageList();
        $this->assertStringContainsString($error, "Missing 'env' parameter on Test configuration");
    }

    public function test_bad_package_list_missing_load()
    {
        $provider = new  DefaultPackageProviderService($this->app,['Test'=>[
            'env'=>'ALL',
        ]],[]);
        $error = $provider->validatePackageList();
        $this->assertStringContainsString($error, "Missing 'load' parameter on Test configuration");
    }


    public function test_bad_package_list_missing_only_debug()
    {
        $provider = new  DefaultPackageProviderService($this->app,['Test'=>[
            'env'=>'ALL',
            'load'=>true,
        ]],[]);
        $error = $provider->validatePackageList();
        $this->assertStringContainsString($error, "Missing 'only_debug' parameter on Test configuration");
    }

    public function test_bad_package_list_missing_debug()
    {
        $provider = new  DefaultPackageProviderService($this->app,['Test'=>[
            'env'=>'ALL',
            'load'=>true,
            'only_debug' => false,
        ]],[]);
        $error = $provider->validatePackageList();
        $this->assertStringContainsString($error, "Missing 'debug' parameter on Test configuration");
    }

    public function test_bad_package_list_missing_package_provider()
    {
        $provider = new  DefaultPackageProviderService($this->app,['Test'=>[
            'env'=>'ALL',
            'load'=>true,
            'only_debug' => false,
            'debug' => true
        ]],[]);
        $error = $provider->validatePackageList();
        $this->assertStringContainsString($error, "Missing 'package_provider' parameter on Test configuration");
    }

    public function test_pass_validatePackageList()
    {
        $provider = new  DefaultPackageProviderService($this->app,['Test'=>[
            'env'=>'ALL',
            'load'=>true,
            'only_debug' => false,
            'debug' => true,
            'package_provider' => 'Gianfriaur\PackageLoader\Tests\Service\DefaultPackageProviderService\TestBadPackageProvider'
        ]],[]);
        $error = $provider->validatePackageList();
        $this->assertTrue($error);
    }

    public function test_BadPackageProviderException_on_load()
    {
        $provider = new  DefaultPackageProviderService($this->app,['Test'=>[
            'env'=>'ALL',
            'load'=>true,
            'only_debug' => false,
            'debug' => true,
            'package_provider' => 'Gianfriaur\PackageLoader\Tests\Service\DefaultPackageProviderService\TestBadPackageProvider'
        ]],[]);
        $error = $provider->validatePackageList();


        $this->expectException(BadPackageProviderException::class);
        $this->expectExceptionMessage('Gianfriaur\PackageLoader\Tests\Service\DefaultPackageProviderService\TestBadPackageProvider not extend Gianfriaur\PackageLoader\PackageProvider\AbstractPackageProvider');
        $provider->load();
    }

    public function test_pass_load()
    {
        $provider = new  DefaultPackageProviderService($this->app,['Test'=>[
            'env'=>'ALL',
            'load'=>true,
            'only_debug' => false,
            'debug' => true,
            'package_provider' => 'Gianfriaur\PackageLoader\Tests\Service\DefaultPackageProviderService\TestValidPackageProvider'
        ]],[]);
        $error = $provider->validatePackageList();
        $this->assertTrue($error);
        $provider->load();
    }
}