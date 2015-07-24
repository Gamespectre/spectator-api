<?php

namespace Spectator\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Events\Event;

class PackageDataRetrievedHandler
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
        $packageId = $event->package->packageId;
        \Cache::put($packageId, serialize($event->package), 15);
    }
}
