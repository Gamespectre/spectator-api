<?php

namespace Spectator\Listeners;

use Spectator\Events\PackageSaveStarted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PackageSaveHandler implements ShouldQueue
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
     * @param  PackageSaveStarted  $event
     * @return void
     */
    public function handle(PackageSaveStarted $event)
    {
        $event->package->save();
    }
}
