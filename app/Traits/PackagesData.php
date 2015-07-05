<?php

namespace Spectator\Traits;

use Illuminate\Support\Collection;
use Spectator\Events\Api\PackageDone;
use Spectator\Events\Api\Youtube\Playlists\PlaylistsRetrieved;
use Spectator\Events\Event;
use Spectator\Services\App\Package;

trait PackagesData
{
    protected $package;
    private $name;
    private $method;
    private $args;
    private $data = false;

    public function pack(Package $package)
    {
        $this->package = $package;
        $args = $package->getArgs($this->args);
        $data = call_user_func_array([$this, $this->method], $args->all());

        if(!$data instanceof Collection) {
            $data = collect([$data]);
        }

        $this->data = $data;

        $this->done();
    }

    public function packEventPackage($event)
    {
        if($this->data === false) {
            $this->pack($event->data);
        }
        else {
            $this->done();
        }
    }

    public function done()
    {
        if($this->package->checkDone()) {
            \Event::fire(new PackageDone($this->package));
        }
        else {
            \Event::fire(new $this->event($this->package));
        }
    }

    public function setPackageData($name, $method, Collection $args)
    {
        $this->name = $name;
        $this->method = $method;
        $this->args = $args;
    }

    public function getData()
    {
        return $this->data;
    }
}