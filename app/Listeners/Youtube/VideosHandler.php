<?php

namespace Spectator\Listeners\Youtube;

use Spectator\Events\Youtube\VideosRetrieved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VideosHandler implements ShouldQueue
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
     * @param  VideosRetrieved  $event
     * @return void
     */
    public function handle(VideosRetrieved $event)
    {
        $event->data->packNext();
    }
}
