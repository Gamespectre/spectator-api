<?php

namespace Spectator\Listeners\Youtube;

use Spectator\Events\Youtube\ChannelsRetrieved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChannelsHandler implements ShouldQueue
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
     * @param  ChannelsRetrieved  $event
     * @return void
     */
    public function handle(ChannelsRetrieved $event)
    {
        $event->data->packNext();
    }
}
