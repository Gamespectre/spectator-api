<?php

namespace Spectator\Listeners\Api;

use Spectator\Events\Api\PackageDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        dd(json_encode($event->package));
    }
}
