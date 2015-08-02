<?php

namespace Spectator\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Spectator\Services\App\Package;

class PackageSaved extends Event
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
    public function __construct($package)
    {
        $this->package = $package;
    }
}
