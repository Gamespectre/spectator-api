<?php

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Collection;
use Spectator\Datamodels\Generic;

class DatamodelTest extends TestCase
{
    public $testData;

    public function setUp()
    {
        parent::setUp();

        $this->testData = collect([
            ['title' => 'test1', 'id' => 1],
            ['title' => 'test2', 'id' => 2]
        ]);
    }

    public function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }

    public function setupTestModel($name)
    {
        $mock = Mockery::mock($name);
        $mock->shouldIgnoreMissing();
        \App::instance($name, $mock);

        return $mock;
    }

    public function testCreateDatamodel()
    {
        $mockModel = $this->setupTestModel(Spectator\Game::class);
        $mockDbQuery = Mockery::mock('db');
        $mockDbQuery->shouldIgnoreMissing();

        $mockModel->shouldReceive('where')->andReturn($mockDbQuery)->twice();
        $mockDbQuery->shouldReceive('first')->twice();

        $datamodelCollection = Generic::createData($this->testData);

        $datamodel = $datamodelCollection->first();

        $this->assertInstanceOf(Collection::class, $datamodelCollection);
        $this->assertInstanceOf(Collection::class, $datamodel->_internalData);

        $this->assertEquals($datamodel->title, 'test1');
        $this->assertEquals($datamodel->id, 1);
    }

    public function testPersist()
    {
        $mockModel = $this->setupTestModel(Spectator\Game::class);
        $mockModel->shouldReceive('create')->andReturn($mockModel)->once();

        $datamodel = new Generic();
        $datamodel->_internalData = collect($datamodel->transform($this->testData[0]));
        $model = $datamodel->persist();

        $this->assertEquals($mockModel, $datamodel->model);
        $this->assertNotEquals($datamodel->model, false);
    }

    public function testAlreadyPersisted()
    {
        $datamodel = new Generic();
        $datamodel->_internalData = collect($datamodel->transform($this->testData[0]));
        $datamodel->model = true;
        $result = $datamodel->persist();

        $this->assertEquals(false, $result);
        $this->assertTrue($datamodel->isPersisted());
    }

    public function testSerialze()
    {
        $mockModel = $this->setupTestModel(Spectator\Game::class);
        $mockDbQuery = Mockery::mock('db');
        $mockDbQuery->shouldIgnoreMissing();

        $mockModel->shouldReceive('where')->andReturn($mockDbQuery)->twice();
        $mockDbQuery->shouldReceive('first')->twice();

        $datamodelCollection = Generic::createData($this->testData);

        $datamodel = $datamodelCollection->first();

        $json = json_encode($datamodel);

        $this->assertJson($json);
        $this->assertEquals(json_decode($json)->title[0], $datamodel->title);
    }
}