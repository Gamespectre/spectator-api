<?php

namespace Spectator\Events\Api\Youtube;

use Spectator\Events\Event;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;

class PlaylistsRetrieved extends Event
{
    use SerializesModels;

    /**
     * @var Collection
     */
    public $videos;

    /**
     * Create a new event instance.
     *
     * @param Collection $videos
     */
    public function __construct(Collection $videos)
    {
        $this->videos = $videos;
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
