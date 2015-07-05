<?php

namespace Spectator\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('playlist', 'Spectator\Services\Youtube\PlaylistService');
        $this->app->singleton('video', 'Spectator\Services\Youtube\VideoService');
        $this->app->singleton('channel', 'Spectator\Services\Youtube\ChannelService');
    }
}
