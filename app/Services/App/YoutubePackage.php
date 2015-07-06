<?php

namespace Spectator\Services\App;

use Spectator\Repositories\YoutubeRepository;

class YoutubePackage extends Package
{
    protected $requiredParams = ['game'];

    public function saveOnly(Collection $resourceIds)
    {
        // TODO: Implement when I have a frontend
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