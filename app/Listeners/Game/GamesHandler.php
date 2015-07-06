<?php

namespace Spectator\Listeners\Game;

use Spectator\Events\Game\GameRetrieved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GamesHandler
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GameRetrieved  $event
     * @return void
     */
    public function handle(GameRetrieved $event)
    {
        //dd($event);
    }
}
