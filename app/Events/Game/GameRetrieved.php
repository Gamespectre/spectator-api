<?php

namespace Spectator\Events\Game;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spectator\Services\App\Package;

class GameRetrieved extends Event
{
    use SerializesModels;
    /**
     * @var Package
     */
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
