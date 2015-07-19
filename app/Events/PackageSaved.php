<?php

namespace Spectator\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Spectator\Services\App\Package;

class PackageSaved extends Event implements ShouldBroadcast
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
    public function __construct($data)
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
        return [$this->data['channel']];
    }
}
