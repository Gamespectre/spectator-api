<?php


namespace Spectator\Services\App;


use Spectator\Services\ApiService;

class PackageService
{
    /**
     * @var ApiService
     */
    private $service;
    private $action;
    private $method;
    private $data = false;

    public function __construct(ApiService $service, $action)
    {
        $this->service = $service;
        $this->action = $action;
        $this->method = $this->service->actions[$action];
    }

    public function execute(array $args)
    {
        $this->data = call_user_func_array([$this->service, $this->method], $args);
    }

    public function getData()
    {
        return $this->data;
    }

    public static function create($serviceName, $action)
    {
        return new static(\App::make($serviceName), $action);
    }
}