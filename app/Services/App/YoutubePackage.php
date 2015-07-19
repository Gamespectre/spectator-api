<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;
use Spectator\Repositories\YoutubeRepository;

class YoutubePackage extends Package
{
    protected $requiredParams = ['game'];

    public function saveOnly(Collection $data)
    {
        $data = $this->services->map(function($service, $key) {
            return $service->getData();
        });

        \App::make(YoutubeRepository::class)->saveAll($data, $this->_params->get('game'));
    }

    /**
     * Saves all datamodels in all services to the database.
     */
    public function saveAll()
    {
        $data = $this->services->map(function($service, $key) {
            return $service->getData();
        });

        \App::make(YoutubeRepository::class)->saveAll($data, $this->_params->get('game'));
    }
}