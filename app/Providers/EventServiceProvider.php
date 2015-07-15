<?php

namespace Spectator\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Search
        'Spectator\Events\Game\Search' => [
            'Spectator\Listeners\Game\SearchHandler'
        ],
        'Spectator\Events\Youtube\Search' => [
            'Spectator\Listeners\Youtube\SearchHandler'
        ],

        // Resources
        'Spectator\Events\Game\GameRetrieved' => [
            'Spectator\Listeners\Game\GamesHandler'
        ],
        'Spectator\Events\PackageDone' => [
            'Spectator\Listeners\PackageDoneHandler'
        ],
        'Spectator\Events\SaveCachedPackage' => [
            'Spectator\Listeners\PackageCacheSaveHandler'
        ],
        'Spectator\Events\Youtube\PlaylistsRetrieved' => [
            'Spectator\Listeners\Youtube\PlaylistsHandler'
        ],
        'Spectator\Events\Youtube\VideosRetrieved' => [
            'Spectator\Listeners\Youtube\VideosHandler'
        ],
        'Spectator\Events\Youtube\ChannelsRetrieved' => [
            'Spectator\Listeners\Youtube\ChannelsHandler'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
