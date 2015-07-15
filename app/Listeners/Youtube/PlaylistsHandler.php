<?php

namespace Spectator\Listeners\Youtube;

use Spectator\Events\Youtube\PlaylistsRetrieved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlaylistsHandler implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PlaylistsRetrieved  $event
     * @return void
     */
    public function handle(PlaylistsRetrieved $event)
    {
        $event->data->packNext();
    }
}
