<?php

namespace Spectator\Events\Api;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spectator\Services\App\Package;

class PackageDone extends Event
{
    use SerializesModels;

    /**
     * @var Package
     */
    public $package;

    /**
     * Create a new event instance.
     *
     * @param Package $package
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
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
