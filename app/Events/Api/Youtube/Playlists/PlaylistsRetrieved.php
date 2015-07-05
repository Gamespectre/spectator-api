<?php

namespace Spectator\Events\Api\Youtube\Playlists;

use Spectator\Events\Event;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;

class PlaylistsRetrieved extends Event
{
    use SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @param mixed $data
     */
    public function __construct($data)
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
