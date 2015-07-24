<?php

namespace Spectator\Listeners;

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
     * @param  PackageError  $event
     * @return void
     */
    public function handle(PackageError $event)
    {
        $packageId = $event->package->packageId;
        \Cache::forget($packageId);
    }
}
