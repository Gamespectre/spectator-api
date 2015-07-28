<?php

namespace Spectator\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

set_time_limit(0);

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Spectator\Console\Commands\GetGame::class,
        \Spectator\Console\Commands\GetVideos::class,
        \Spectator\Console\Commands\CreateBaseUser::class,
        \Spectator\Console\Commands\CreateAnonUser::class,
        \Spectator\Console\Commands\PopulateContent::class,
        \Spectator\Console\Commands\UpdateContent::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('gamespectre:content:populate')
                 ->everyFiveMinutes()->withoutOverlapping()
                 ->sendOutputTo(storage_path('logs/contentpopulate.log'));

        $schedule->command('gamespectre:content:update')
                 ->hourly()->withoutOverlapping()
                 ->sendOutputTo(storage_path('logs/contentpopulate.log'));
    }
}
