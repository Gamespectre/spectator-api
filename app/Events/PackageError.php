<?php

namespace Spectator\Events;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spectator\Services\App\Package;

class PackageError extends Event implements ShouldBroadcast
{
    use SerializesModels;
    /**
     * @var Package
     */
    public $package;
    /**
     * @var
     */
    public $error;

    /**
     * Create a new event instance.
     *
     * @param Package $package
     * @param $errorMessage
     */
    public function __construct(Package $package, $errorMessage)
    {
        $this->package = $package;
        $this->error = $errorMessage;
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
            "success" => false,
            "error" => $this->error,
            "id" => $this->package->packageId,
            "channel" => $this->package->getChannel()
        ];
    }
}
