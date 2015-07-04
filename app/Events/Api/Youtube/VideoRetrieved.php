<?php

namespace Spectator\Events\Api\Youtube;

use Spectator\Events\Event;
use Spectator\Datamodels\Video;
use Illuminate\Queue\SerializesModels;

class PlaylistRetrieved extends Event
{
    use SerializesModels;

    /**
     * @var Video
     */
    public $video;

    /**
     * Create a new event instance.
     *
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
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
