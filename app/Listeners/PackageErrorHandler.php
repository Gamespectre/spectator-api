<?php

namespace Spectator\Listeners;

use Spectator\Events\Event;
use Spectator\Events\PackageError;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PackageErrorHandler implements ShouldQueue
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
        \Cache::forget($packageId);
    }
}
