<?php

namespace Spectator\Listeners\Youtube;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Events\Youtube\Search;
use Spectator\Services\App\YoutubePackage;
use Spectator\Services\Youtube\SearchService;

class SearchHandler implements ShouldQueue
{
    /**
     * @var SearchService
     */
    private $search;

    /**
     * Create the event listener.
     * @param SearchService $search
     */
    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    /**
     * Handle the event.
     * @param Search $event
     */
    public function handle(Search $event)
    {
        $query = $this->search->getSearchQueryForGame($event->data['game']);
        $results = isset($event->data['results']) ? $event->data['results'] : 10;
        $channel = $event->data['channel'];

        // Create a new package with parameters
        $package = YoutubePackage::create([
            'game' => $event->data['game'],
            'query' => $query,
            'results' => $results,
            'force' => false,
            'channel' => $channel
        ]);

        // Add all services you want to pack
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

        // Trigger the first service. The rest will follow through event cascading.
        $package->packNext();
    }
}
