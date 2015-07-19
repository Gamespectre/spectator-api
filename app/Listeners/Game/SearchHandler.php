<?php

namespace Spectator\Listeners\Game;

use Spectator\Events\Game\Search;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Services\App\GamePackage;

class SearchHandler implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Search  $event
     * @return void
     */
    public function handle(Search $event)
    {
        $query = $event->data['query'];
        $method = $event->data['method'];
        $channel = $event->data['channel'];

        $package = GamePackage::create([
            'query' => $query,
            'channel' => $channel
        ]);

        $package->addService('game', [
            'action' => $method,
            'args' => ['query']
        ]);

        $package->packNext();
    }
}
