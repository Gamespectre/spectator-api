<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;
use Spectator\Exceptions\PackageRequiredParamException;
use Spectator\Exceptions\ServiceUnboundException;

abstract class Package implements \JsonSerializable {

    protected $_params;
    protected $services;

    public function __construct($data)
    {
        if(!empty($data))
        {
            $this->setData($data);
        }
    }

    public function addService($name, array $props)
    {
        if(\App::bound($name)) {
            $service = \App::make($name);
            $args = collect($props['args']);
            $method = $service->actions[$props['action']];

            $service->setPackageData($name, $method, $args);
            $this->services->put($name, $service);
        }
        else throw new ServiceUnboundException(
            "Service '" . $name . "'' is not found in the service container!"
        );
    }

    public function trigger($serviceName)
    {
        if($this->services->has($serviceName))
        {
            $this->services->get($serviceName)->pack($this);
        }
        else throw new ServiceUnboundException(
            "Service '" . $name . "'' is not found in this package!"
        );
    }

    public function getArgs(Collection $args)
    {
        return $args->map(function($arg, $key) {

            if($this->_params->has($arg)) {
                return $this->_params->get($arg);
            }

            return $this->getDependency($arg);
        });
    }

    public static function create(array $data)
    {
        $package = new static($data);
        return $package;
    }

    public function serialize()
    {
        return $this->services->map(function($item, $key) {
            return $item->getData();
        })->merge($this->_params->all())->toJson();
    }

    public function __toString()
    {
        return $this->serialize();
    }

    public function __sleep()
    {
        return ["_params", "services"];
    }

    public function jsonSerialize()
    {
        return $this->serialize();
    }

    private function checkRequiredParams($args)
    {
        collect($this->requiredParams)->each(function($item, $key) use ($args) {
            if(!$args->has($item)) {
                throw new PackageRequiredParamException(
                    "The package requires the '" . $item . "' parameter!"
                );
            }
        });
    }

    private function getDependency($name)
    {
        if($this->services->has($name)) {
            return $this->services->get($name)->getData();
        }
    }

    protected function setData(array $data)
    {
        $this->_params = collect($data);
        $this->services = collect([]);

        $this->checkRequiredParams($this->_params);
    }
}