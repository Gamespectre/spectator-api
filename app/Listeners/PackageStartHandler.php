<?php

namespace Spectator\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Events\Event;

class PackageStartHandler implements ShouldQueue
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
     * @param Event $event
     */
    public function handle(Event $event)
    {
        $event->package->pack();
    }
}
