<?php

namespace Spectator\Events\Youtube;

use Illuminate\Queue\SerializesModels;
use Spectator\Events\Event;

class Search extends Event
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
