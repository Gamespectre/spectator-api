<?php

namespace Spectator\Listeners;

use Spectator\Events\NewContentAvailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Processing\Series\Populate;

class NewContentHandler implements ShouldQueue
{
    /**
     * @var ContentAdmin
     */
    private $admin;

    /**
     * Create the event listener.
     * @param Populate $populate
     */
    public function __construct(Populate $populate)
    {
        $this->populate = $populate;
    }

    /**
     * Handle the event.
     *
     * @param  NewContentAvailable  $event
     * @return void
     */
    public function handle(NewContentAvailable $event)
    {
        //$this->populate->execute();
    }
}
