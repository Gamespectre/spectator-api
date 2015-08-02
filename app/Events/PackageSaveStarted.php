<?php

namespace Spectator\Events;

use Spectator\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spectator\Services\App\Package;

class PackageSaveStarted extends Event
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
}
