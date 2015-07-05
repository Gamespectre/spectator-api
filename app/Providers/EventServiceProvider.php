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
        'Spectator\Events\Api\Youtube\Search' => [
            'Spectator\Listeners\Api\Youtube\SearchHandler'
        ],
        'Spectator\Events\Api\PackageDone' => [
            'Spectator\Listeners\Api\PackageDoneHandler'
        ],
    ];

    protected $subscribe = [
        'Spectator\Services\Youtube\VideoService',
        'Spectator\Services\Youtube\ChannelService',
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
