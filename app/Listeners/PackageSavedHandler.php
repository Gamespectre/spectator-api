<?php

namespace Spectator\Listeners;

use Spectator\Events\PackageSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PackageSavedHandler implements ShouldQueue
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
     * @param  PackageSaved  $event
     * @return void
     */
    public function handle(PackageSaved $event)
    {
        $packageId = $event->package->packageId;
        \Cache::forget($packageId);
    }
}
