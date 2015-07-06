<?php

namespace Spectator\Listeners;

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
        $event->data->saveAll();
        dd("Package saved");
    }
}
