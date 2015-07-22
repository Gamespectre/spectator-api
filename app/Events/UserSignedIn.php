<?php

namespace Spectator\Events;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserSignedIn extends Event implements ShouldBroadcast
{
    use SerializesModels;
    /**
     * @var
     */
    public $data;
    /**
     * @var
     */
    private $channel;

    /**
     * Create a new event instance.
     *
     * @param $data
     * @param $channel
     */
    public function __construct($data, $channel)
    {
        $this->data = [
            'user' => $data['user'],
            'token' => $data['token']
        ];

        $this->channel = $channel;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [$this->channel];
    }
}
