<?php

namespace Spectator\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Spectator\Events\Event;
use Spectator\Services\App\Package;

class PackageDone extends Event implements ShouldBroadcast
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
    public function __construct(Package $data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        if($this->data->getParams()->has('channel')) {
            return [$this->data->getParams()->get('channel')];
        }
        else {
            return [];
        }
    }

    public function broadcastWith()
    {
        $response = [
            "id" => $this->data->packageId,
            "channel" => $this->data->getParams()->get('channel'),
            "query" => $this->data->getParams()->get('query')
        ];

        return $response;
    }
}
