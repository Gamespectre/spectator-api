<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spectator\Services\App\GenericPackage as Package;

class PackageTest extends TestCase
{
    public $package;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }

    public function setupTestService($name = 'test')
    {
        $mock = Mockery::mock($name);
        $mock->shouldIgnoreMissing();
        $mock->actions = [ 'get' => 'testMethod' ];
        \App::instance($name, $mock);

        return $mock;
    }
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testPackageCreation()
    {
        $package = Package::create([ 'game' => 'game' ]);
        $this->assertArrayHasKey('game', $package->getParams()->all());
        $this->assertTrue($package->getParams()->get('game') === 'game');
    }

    public function testPackageAddService()
    {
        $package = Package::create([ 'game' => 'game' ]);
        $mock = $this->setupTestService();

        $mock->shouldReceive('setPackageData')->with('test', 'testMethod', ['game'])->once();

        $package->addService('test', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $this->assertTrue($package->getServices()->has('test'));
    }

    public function testAddServiceNotFound()
    {
        $package = Package::create([ 'game' => 'game' ]);
        $this->setExpectedException('Spectator\Exceptions\ServiceUnboundException');
        $package->addService('notfound', ['action' => 'derp', 'args' => ['none']]);
    }

    public function testTriggerService()
    {
        $package = Package::create([ 'game' => 'game' ]);
        $mock = $this->setupTestService();

        $mock->shouldReceive('pack')->with($package)->once();

        $package->addService('test', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $package->trigger('test');
    }

    public function testGetNextServiceNoDeps()
    {
        $package = Package::create([ 'game' => 'game' ]);
        $mock = $this->setupTestService();

        $mock->args = collect(['game']);
        $mock->shouldReceive('getData')->once()->andReturn(false);

        $package->addService('test', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $nextService = $package->getNext();
        $this->assertSame($nextService, $mock);
    }

    public function testGetNextServiceWithDeps()
    {
        $package = Package::create([ 'game' => 'game' ]);

        $mock = $this->setupTestService();
        $mock->args = collect(['test2']);
        $mock->shouldReceive('getData')->once()->andReturn(false);

        $mock2 = $this->setupTestService('test2');
        $mock2->args = collect(['game']);
        $mock2->shouldReceive('getData')->twice()->andReturn(true);

        // The order you add services should NOT matter
        $package->addService('test', [
            'action' => 'get',
            'args' => ['test2']
        ]);

        $package->addService('test2', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $nextService = $package->getNext();
        $this->assertSame($nextService, $mock);
    }

    public function testGetNextServiceFails()
    {
        // TODO: add PackageDone event as fallback if services have data.
        $this->setExpectedException('Spectator\Exceptions\UnresolvablePackageException');
        $package = Package::create([ 'game' => 'game' ]);

        // Both services depend on each other. Not good!
        $mock = $this->setupTestService();
        $mock->args = collect(['test2']);
        $mock->shouldReceive('getData')->andReturn(false);

        $mock2 = $this->setupTestService('test2');
        $mock2->args = collect(['test']);
        $mock2->shouldReceive('getData')->andReturn(false);

        // The order you add services should NOT matter
        $package->addService('test', [
            'action' => 'get',
            'args' => ['test2']
        ]);

        $package->addService('test2', [
            'action' => 'get',
            'args' => ['test']
        ]);

        $package->getNext();
    }

    public function testArgsResolveFromParams()
    {
        $package = Package::create([ 'game' => '123' ]);
        $resolvedArgs = $package->getArgs(collect(['game']))->first();

        $this->assertTrue($resolvedArgs === '123');
    }

    public function testArgsResolveFromDeps()
    {
        $package = Package::create([ 'game' => '123' ]);

        $mock = $this->setupTestService();
        $mock->shouldReceive('getData')->once()->andReturn(true);

        $package->addService('test', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $resolvedArgs = $package->getArgs(collect(['test']))->first();
        $this->assertTrue($resolvedArgs === true);
    }

    public function testCheckDone()
    {
        $package = Package::create([ 'game' => '123' ]);

        $mock = $this->setupTestService();
        $mock->shouldReceive('getData')->andReturn(true);

        $package->addService('test', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $this->assertTrue($package->checkDone() === true);

        $mock2 = $this->setupTestService('test2');
        $mock2->shouldReceive('getData')->andReturn(false);

        $package->addService('test2', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $this->assertTrue($package->checkDone() === false);
    }

    public function testGetDataFromService()
    {
        $package = Package::create([ 'game' => '123' ]);

        $mock = $this->setupTestService();
        $mock->shouldReceive('getData')->andReturn("service data");

        $package->addService('test', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $this->assertTrue($package->getData('test') === "service data");
    }

    public function testSerializeJson()
    {
        $package = Package::create([ 'game' => '123' ]);

        $mock = $this->setupTestService();
        $mock->shouldReceive('getData')->andReturn(collect(["data" => "service data"]));

        $package->addService('test', [
            'action' => 'get',
            'args' => ['game']
        ]);

        $jsonPackage = json_encode($package);
        $arrayPackage = json_decode($jsonPackage, true);

        $this->assertJson($jsonPackage);
        $this->assertTrue($arrayPackage['test']['data'] === "service data");
        $this->assertTrue($arrayPackage['game'] === "123");
    }
}
