<?php

namespace Spectator\Processing\Games;

use App;
use Illuminate\Foundation\Bus\DispatchesJobs;
use League\Pipeline\Pipeline;
use Spectator\Game;
use Spectator\Interfaces\ProcessorInterface;
use Spectator\Jobs\Asyncify;
use Spectator\Processing\Games\Pipeline\SearchSeriesForGame;
use Spectator\Processing\Processor;
use Spectator\Processing\Series\Pipeline\GetSeriesCreator;
use Spectator\Processing\Series\Pipeline\GetSeriesVideos;
use Spectator\Processing\Series\Pipeline\SaveCreator;
use Spectator\Processing\Series\Pipeline\SaveSeries;
use Spectator\Processing\Series\Pipeline\SaveSeriesData;
use Spectator\Processing\Series\Pipeline\SaveVideos;
use Spectator\Processing\Series\Populate as PopulateSeries;

class Populate extends Processor implements ProcessorInterface
{
    use DispatchesJobs;

    /**
     * @var Game
     */
    public $game;
    /**
     * @var PopulateSeries
     */
    public $populateSeries;

    public function __construct(Game $game, PopulateSeries $populateSeries)
    {
        parent::__construct("gamepopulate");
        $this->game = $game;
        $this->populateSeries = $populateSeries;
    }

    public function execute()
    {
        $games = $this->game
            ->doesntHave('series')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->log("Populating " . $games->count() . " games.");

        $games->each(function($model) {
            $data = collect([
                'config' => [
                    'mode' => 'new'
                ],
                'game' => $model
            ]);

            $this->dispatch(new Asyncify($data, $this->getPipeline()));
        });
    }

    public function getPipeline()
    {
        $seriesPipeline = $this->populateSeries->getPipeline();

        $pipeline = (new Pipeline)
            ->pipe(App::make(SearchSeriesForGame::class))
            ->pipe(App::make(SaveSeriesData::class))
            ->pipe($seriesPipeline);

        return $pipeline;
    }
}