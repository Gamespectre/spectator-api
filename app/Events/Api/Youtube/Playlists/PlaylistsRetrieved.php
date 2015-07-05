<?php

namespace Spectator\Events\Api\Youtube;

use Spectator\Events\Event;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;

class PlaylistsRetrieved extends Event
{
    use SerializesModels;

    public $playlists;

    /**
     * Create a new event instance.
     *
     * @param Collection $playlists
     */
    public function __construct(Collection $playlists)
    {
        $this->playlists = $playlists;
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
