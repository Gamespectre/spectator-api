<?php

namespace Spectator\Events\Youtube;

use Illuminate\Queue\SerializesModels;
use Spectator\Events\Event;
use Spectator\Services\App\Package;

class PlaylistsRetrieved extends Event
{
    use SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @param mixed $data
     */
    public function __construct(Package $data)
    {
        $this->data = $data;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
