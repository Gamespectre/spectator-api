<?php

namespace Spectator\Services\App;

use Spectator\Services\App\PackageService;

abstract class Package {

    protected $_params;
    protected $services;

    public function __construct($data)
    {
        if(!empty($data))
        {
            $this->setData($data);
        }
    }

    public function addService($name, $props)
    {
        $this->services->put($name, [
            'service' => PackageService::create(
                $name,
                $props['action']
            ),
            'args' => collect($props['args'])
        ]);
    }

    public function execService($name)
    {
        $serviceItem = $this->services->get($name);

        if(is_null($serviceItem)) {
            return false;
        }

        $args = $serviceItem['args']->map(function($item, $key) {
            return $this->getArg($item);
        });

        $serviceItem['service']->execute($args->all());
    }

    public function __get($name)
    {
        return $this->_params->get($name);
    }

    public static function create(array $data)
    {
        $package = new static($data);
        return $package;
    }

    private function getArg($arg)
    {
        if($this->_params->has($arg)) {
            return $this->_params->get($arg);
        }

        return $this->getDependency($arg);
    }

    private function getDependency($name)
    {
        if($this->services->has($name)) {
            return $this->services->get($name)['service']->getData();
        }
    }

    protected function setData(array $data)
    {
        $params = collect($data);

        collect($this->requiredParams)->each(function($item, $key) use ($params) {
            if(!$params->has($item)) {
                throw new Spectator\Exceptions\PackageRequiredParamException(
                    "The " . $item . " parameter is required!"
                );
            }
        });

        $this->_params = $params;
        $this->services = collect([]);
    }
}