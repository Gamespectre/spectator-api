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
        // Socialite
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            'SocialiteProviders\YouTube\YouTubeExtendSocialite@handle'
        ],

        // Search
        'Spectator\Events\PackageStarted' => [
            'Spectator\Listeners\PackageStartHandler'
        ],

        // Resources
        'Spectator\Events\PackageDataRetrieved' => [
            'Spectator\Listeners\PackageDataRetrievedHandler'
        ],

        // Saving
        'Spectator\Events\PackageSaveStarted' => [
            'Spectator\Listeners\PackageSaveHandler'
        ],
        'Spectator\Events\PackageSaved' => [
            'Spectator\Listeners\PackageSavedHandler'
        ],

        // It's not gone well
        'Spectator\Events\PackageError' => [
            'Spectator\Listeners\PackageErrorHandler'
        ],
        'Spectator\Events\PackageEmpty' => [
            'Spectator\Listeners\PackageErrorHandler'
        ],

        // Background tasks
        'Spectator\Events\NewContentAvailable' => [
            'Spectator\Listeners\NewContentHandler'
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
