<?php

namespace Spectator\Events\Api\Youtube;

use Spectator\Events\Event;
use Spectator\Datamodels\Playlist;
use Illuminate\Queue\SerializesModels;

class PlaylistRetrieved extends Event
{
    use SerializesModels;

    public $playlist;

    /**
     * Create a new event instance.
     *
     * @param Playlist $playlist
     */
    public function __construct(Playlist $playlist)
    {
        $this->playlist = $playlist;
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
