<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;
use Spectator\Exceptions\PackageRequiredParamException;
use Spectator\Exceptions\ServiceUnboundException;
use Spectator\Exceptions\UnresolvablePackageException;

abstract class Package implements \JsonSerializable {

    public $packageId;
    protected $_params;
    protected $services;

    public function __construct($data)
    {
        if(!empty($data))
        {
            $this->setData($data);
        }

        $this->packageId = uniqid('package-');
    }

    public static function create(array $data)
    {
        $package = new static($data);
        return $package;
    }

    public function addService($name, array $props)
    {
        if(\App::bound($name)) {
            $service = \App::make($name);
            $args = $props['args'];
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
            "Service '" . $serviceName . "'' is not found in this package!"
        );
    }

    public function packNext()
    {
        $this->getNext()->pack($this);
    }

    public function getNext()
    {
        $nextService = $this->services->filter(function($service) {
            $unmetDeps = $service->args->reject(function($arg) {
                if($this->_params->has($arg)) return true;
                if($this->services->has($arg)) {
                    return $this->services->get($arg)->getData() !== false;
                }
                else return false;
            });

            return $service->getData() === false && $unmetDeps->isEmpty();
        });

        if($nextService->isEmpty()) {
            // TODO: add PackageDone event as fallback if services have data.
            throw new UnresolvablePackageException(
                "Check services for the package. They cannot be resolved."
            );
        }

        return $nextService->first();
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

    public function checkDone()
    {
        return $this->services->reject(function($item) {
            return $item->getData() !== false;
        })->isEmpty();
    }

    public function getServices()
    {
        return $this->services;
    }

    public function getData($serviceName = false)
    {
        if($serviceName !== false && $this->services->has($serviceName))
        {
            return $this->services->get($serviceName)->getData();
        }
        else if($serviceName === false) {
            return $this->services;
        }

        return false;
    }

    private function checkRequiredParams($args)
    {
        if(isset($this->requiredParams)) {
            collect($this->requiredParams)->each(function($item, $key) use ($args) {
                if(!$args->has($item)) {
                    throw new PackageRequiredParamException(
                        "The package requires the '" . $item . "' parameter!"
                    );
                }
            });
        }
    }

    private function getDependency($name)
    {
        if($this->services->has($name)) {
            return $this->services->get($name)->getData();
        }

        return false;
    }

    protected function setData(array $data)
    {
        $this->_params = collect($data);
        $this->services = collect([]);

        $this->checkRequiredParams($this->_params);
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function serialize()
    {
        return $this->services->map(function($item, $key) {
            return $item->getData();
        })->merge($this->_params->all())->put('id', $this->packageId)->toArray();
    }

    public function __toString()
    {
        return $this->serialize();
    }

    public function __sleep()
    {
        return ["_params", "services", "packageId"];
    }

    public function jsonSerialize()
    {
        return $this->serialize();
    }

    abstract public function saveAll();
    abstract public function saveOnly(Collection $data);
}