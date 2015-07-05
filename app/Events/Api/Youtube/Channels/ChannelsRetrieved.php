<?php

namespace Spectator\Events\Api\Youtube\Channels;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;
use Spectator\Services\App\Package;

class ChannelsRetrieved extends Event
{
    use SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @param Package $data
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
