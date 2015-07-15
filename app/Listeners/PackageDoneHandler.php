<?php

namespace Spectator\Listeners;

use Spectator\Events\PackageDone;
use Spectator\Events\PackageSaved;

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
        \Cache::put($packageId, 15, serialize($event->data));

        //event(new PackageSaved($event->data));
    }
}
