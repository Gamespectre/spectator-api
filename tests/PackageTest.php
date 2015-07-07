<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spectator\Services\App\GamePackage;
use Spectator\Services\App\Package;

class PackageTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testPackageCreation()
    {
        $package = GamePackage::create([ 'game' => 'game' ]);

        $this->assertArrayHasKey($package->getParams->all(), 'game');
        $this->assertTrue($this->getParams->get('game') === 'game');
    }
}
