<?php

namespace Spectator\Listeners;

use Spectator\Events\NewContentAvailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Processing\Games\Populate as PopulateGames;
use Spectator\Processing\Series\Populate as PopulateSeries;

class NewContentHandler implements ShouldQueue
{
    /**
     * @var PopulateSeries
     */
    private $populateSeries;
    private $populateGames;

    /**
     * Create the event listener.
     * @param PopulateSeries $populateSeries
     * @param PopulateGames $populateGames
     */
    public function __construct(PopulateSeries $populateSeries, PopulateGames $populateGames)
    {
        $this->populateGames = $populateGames;
        $this->populateSeries = $populateSeries;
    }

    /**
     * Handle the event.
     *
     * @param  NewContentAvailable  $event
     * @return void
     */
    public function handle(NewContentAvailable $event)
    {
        $type = $event->type;

        if($type === 'playlist') {
            $this->populateSeries->execute();
        }
        if($type === 'game') {
            $this->populateGames->execute();
        }
    }
}
