<?php

namespace Spectator\Traits;

use Illuminate\Support\Collection;
use Spectator\Services\App\Package;

trait PackagesData
{
    private $name;
    private $method;
    private $args;
    private $data = false;

    public function pack(Package $package)
    {
        $args = $package->getArgs($this->args);
        $this->data = call_user_func_array([$this, $this->method], $args->all());
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