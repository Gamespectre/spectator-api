<?php

namespace Spectator\Services\App;

use Illuminate\Contracts\Auth\Authenticatable;

class BroadcastService
{
    /**
     * @var Authenticatable
     */
    private $user;

    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function getChannel($base)
    {
        return $this->user->getAuthIdentifier() . '-' . $base;
    }
}