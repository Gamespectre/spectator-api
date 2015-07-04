<?php

namespace Spectator\Events\Api\Youtube;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;

class PlaylistSearch extends Event
{
    use SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @param Collection $data
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
