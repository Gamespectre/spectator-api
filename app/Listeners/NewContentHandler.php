<?php

namespace Spectator\Listeners;

use Spectator\Events\NewContentAvailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Services\App\ContentUpdate;

class NewContentHandler implements ShouldQueue
{
    /**
     * @var ContentAdmin
     */
    private $admin;

    /**
     * Create the event listener.
     * @param ContentUpdate $admin
     */
    public function __construct(ContentUpdate $admin)
    {
        $this->admin = $admin;
    }

    /**
     * Handle the event.
     *
     * @param  NewContentAvailable  $event
     * @return void
     */
    public function handle(NewContentAvailable $event)
    {
        $this->admin->populate();
    }
}
