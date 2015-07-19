<?php

namespace Spectator\Listeners;

use Spectator\Events\PackageSaved;
use Spectator\Events\PackageSaveFailed;
use Spectator\Events\SaveCachedPackage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PackageCacheSaveHandler implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SaveCachedPackage  $event
     * @return void
     */
    public function handle(SaveCachedPackage $event)
    {
        $packageId = $event->data['package'];
        $dataToSave = $event->data['data'];
        $channel = $event->data['channel'];

        $packageData = \Cache::pull($packageId);

        if(is_null($packageData)) {
            event(new PackageSaveFailed([
                'channel' => $channel,
                'message' => 'Package not found in cache. Your session timed out.'
            ]));

            return false;
        }

        $package = unserialize($packageData);

        if($dataToSave === true) {
            $package->saveAll();
        }
        elseif(is_array($dataToSave)) {
            $package->saveOnly(collect($dataToSave));
        }
        else {
            event(new PackageSaveFailed([
                'channel' => $channel,
                'message' => 'Check save data parameters.'
            ]));
        }

        event(new PackageSaved([
            'channel' => $channel,
            'message' => 'Your data is saved.'
        ]));
    }
}
