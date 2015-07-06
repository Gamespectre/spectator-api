<?php

namespace Spectator\Listeners\Game;

use Spectator\Events\Game\Search;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Services\App\GamePackage;

class SearchHandler
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
        $query = $event->data['gameApiId'];

        $package = GamePackage::create([
            'query' => $query
        ]);

        $package->addService('game', [
            'action' => 'get',
            'args' => ['query']
        ]);

        $package->packNext();
    }
}
