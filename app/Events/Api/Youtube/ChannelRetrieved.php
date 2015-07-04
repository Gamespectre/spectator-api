<?php

namespace Spectator\Events\Api\Youtube;

use Spectator\Events\Event;
use Spectator\Datamodels\Channel;
use Illuminate\Queue\SerializesModels;

class ChannelRetrieved extends Event
{
    use SerializesModels;

    public $channel;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
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
