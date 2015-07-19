<?php

namespace Spectator\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Events\PackageDone;

class PackageDoneHandler
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
     * @param  PackageDone  $event
     * @return void
     */
    public function handle(PackageDone $event)
    {
        $packageId = $event->data->packageId;
        \Cache::put($packageId, serialize($event->data), 15);
    }
}
