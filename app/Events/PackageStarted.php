<?php

namespace Spectator\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Spectator\Events\Event;
use Spectator\Services\App\Package;

class PackageStarted extends Event implements ShouldBroadcast
{
    use SerializesModels;

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
        return [$this->package->getChannel()];
    }

    public function broadcastWith()
    {
        return [
            "success" => true,
            "id" => $this->package->packageId,
            "channel" => $this->package->getChannel()
        ];
    }
}
