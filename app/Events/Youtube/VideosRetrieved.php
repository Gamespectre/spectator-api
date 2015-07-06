<?php

namespace Spectator\Events\Youtube;

use Illuminate\Queue\SerializesModels;
use Spectator\Events\Event;
use Spectator\Services\App\Package;

class VideosRetrieved extends Event
{
    use SerializesModels;

    /**
     * @var Collection
     */
    public $data;

    /**
     * Create a new event instance.
     *
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
