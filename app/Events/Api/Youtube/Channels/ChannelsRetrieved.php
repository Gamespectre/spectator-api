<?php

namespace Spectator\Events\Api\Youtube;

use Spectator\Events\Event;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;

class ChannelsRetrieved extends Event
{
    use SerializesModels;

    public $channels;

    /**
     * Create a new event instance.
     *
     * @param Collection $channels
     */
    public function __construct(Collection $channels)
    {
        $this->channels = $channels;
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
