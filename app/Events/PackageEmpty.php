<?php

namespace Spectator\Events;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spectator\Services\App\Package;

class PackageEmpty extends Event implements ShouldBroadcast
{
    use SerializesModels;
    /**
     * @var Package
     */
    public $package;

    /**
     * Create a new event instance.
     * @param Package $package
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    public function broadcastOn()
    {
        return [$this->package->getChannel()];
    }

    public function broadcastWith()
    {
        return [
            "success" => false,
            "id" => $this->package->packageId,
            "channel" => $this->package->getChannel()
        ];
    }
}
