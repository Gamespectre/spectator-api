<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;
use Spectator\Events\PackageDataRetrieved;
use Spectator\Events\PackageEmpty;
use Spectator\Events\PackageError;
use Spectator\Exceptions\PackageRequiredParamException;
use Spectator\Exceptions\ServiceUnboundException;
use Spectator\Exceptions\UnresolvablePackageException;

abstract class Package implements \JsonSerializable {

    public $packageId;
    protected $_params;
    protected $data = false;

    public function __construct($data)
    {
        if(!empty($data))
        {
            $this->initialize($data);
        }

        $this->packageId = uniqid('package-');
    }

    public static function create(array $data)
    {
        $package = new static($data);
        return $package;
    }

    protected function initialize(array $data)
    {
        $this->_params = collect($data);
    }

    public function pack()
    {
        $resource = $this->_params->get('resource');
        $service = $this->resolveService($resource['name']);

        $this->data = $this->execService($service, $service->actions[$resource['method']])
            ->reject(function($item) {
                return $item->isPersisted();
            })->flatten();

        if(empty($this->data) || ($this->data->isEmpty())) {
            event(new PackageEmpty($this));
        }
        else {
            event(new PackageDataRetrieved($this));
        }
    }

    public function update($data) {
        $dataToKeep = collect($data);

        $this->data = $this->data->filter(function($item) use ($dataToKeep) {
            return $dataToKeep->has($item->id) &&
                   (($dataToKeep->get($item->id)['chosen'] === true) ||
                    ($dataToKeep->get($item->id) === true));
        })->map(function($item) use ($dataToKeep) {
            return $item->update($dataToKeep->get($item->id));
        });
    }

    public function resolveService($resource)
    {
        $serviceName = isset($this->handlers[$resource]) ? $this->handlers[$resource] : false;

        if($serviceName !== false && \App::bound($serviceName)) {
            return $service = \App::make($serviceName);
        }
        else {
            event(new PackageError($this, "Service not found."));
        }
    }

    public function execService($service, $method)
    {
        return call_user_func([$service, $method], $this->_params->get('query'));
    }

    public function setChannel($channel)
    {
        $this->_params->put('channel', $channel);
        return $channel;
    }

    public function getChannel()
    {
        return $this->_params->get('channel');
    }

    public function getData()
    {
        return $this->data;
    }

    public function serialize()
    {
        return [
            'params' => $this->_params->toString(),
            'data' => $this->data->toString(),
            'id' => $this->packageId
        ];
    }

    public function jsonSerialize()
    {
        return $this->serialize();
    }

    public function __toString()
    {
        return $this->serialize();
    }

    public function __sleep()
    {
        return ["_params", "data", "packageId"];
    }

    abstract public function save();
}