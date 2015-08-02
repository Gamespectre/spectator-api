<?php

namespace Spectator\Events;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewContentAvailable extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $type;

    /**
     * Create a new event instance.
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
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
