<?php

namespace Spectator\Listeners\Api\Youtube;

use Spectator\Events\Api\Youtube\PlaylistSearch;
use Spectator\Services\App\PackageManager;
use Spectator\Services\App\YoutubePackage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Services\Youtube\SearchService;
use Spectator\Services\Youtube\PlaylistService;

class PlaylistSearchHandler
{
    /**
     * @var SearchService
     */
    private $search;
    /**
     * @var PackageManager
     */
    private $manager;

    /**
     * Create the event listener.
     * @param SearchService $search
     * @param PackageManager $manager
     */
    public function __construct(SearchService $search, PackageManager $manager)
    {
        $this->search = $search;
        $this->manager = $manager;
    }

    /**
     * Handle the event.
     * @param PlaylistSearch $event
     */
    public function handle(PlaylistSearch $event)
    {
        $query = $this->search->getSearchQueryForGame($event->data['game']);
        $results = isset($event->data['results']) ? $event->data['results'] : 10;

        $package = YoutubePackage::create([
            'game' => $event->data['game'],
            'query' => $query,
            'results' => $results,
            'force' => false
        ]);

        $package->addService('playlist', [
            'action' => 'search',
            'args' => ['query', 'results', 'force']
        ]);

        $package->addService('video', [
            'action' => 'playlists',
            'args' => ['playlist', 'force']
        ]);

        $package->addService('channel', [
            'action' => 'videos',
            'args' => ['video', 'force']
        ]);

        $this->manager->pack($package, ['playlist', 'video', 'channel']);
    }
}
