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
        $this->app->bind('playlist', 'Spectator\Services\Youtube\PlaylistService');
        $this->app->bind('video', 'Spectator\Services\Youtube\VideoService');
        $this->app->bind('channel', 'Spectator\Services\Youtube\ChannelService');
        $this->app->bind('game', 'Spectator\Services\Game\GameService');
    }
}
