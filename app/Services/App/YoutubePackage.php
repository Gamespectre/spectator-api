<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;
use Spectator\Events\PackageSaved;
use Spectator\Repositories\YoutubeRepository;

class YoutubePackage extends Package
{
    // List of registered services and their Service Container binding name
    protected $handlers = [
        'playlist' => 'playlist',
        'video' => 'video',
        'channel' => 'channel'
    ];

    /**
     * Saves all datamodels in all services to the database.
     */
    public function save()
    {
        $data = $this->getData();
        $repo = \App::make(YoutubeRepository::class);

        call_user_func([$repo, 'save' . ucfirst(str_plural($this->_params->get('resource')['name']))], $data);

        event(new PackageSaved($this));
    }
}