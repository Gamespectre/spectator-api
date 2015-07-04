<?php

namespace Spectator\Services\App;

use Spectator\Services\Youtube\ChannelService;
use Spectator\Services\Youtube\PlaylistService;
use Spectator\Services\Youtube\VideoService;

class PackageManager
{
    public function __construct()
    {
        //
    }

    /**
     * @param Package $package
     * @param array $order
     */
    public function pack(Package $package, array $order)
    {
        collect($order)->each(function($item, $key) use ($package) {
            $package->execService($item);
        });

        dd($package);
    }
}