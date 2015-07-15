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
    private $data;

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
        if($this->data->getParams()->has('event')) {
            return [$this->data->getParams()->get('event')['channel']];
        }

        return [];
    }
}
