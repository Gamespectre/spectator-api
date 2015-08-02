<?php

namespace Spectator\Processing\Series;

use App;
use Illuminate\Foundation\Bus\DispatchesJobs;
use League\Pipeline\Pipeline;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spectator\Interfaces\ProcessorInterface;
use Spectator\Jobs\Asyncify;
use Spectator\Processing\Processor;
use Spectator\Series;
use Spectator\Processing\Series\Pipeline\GetSeriesCreator;
use Spectator\Processing\Series\Pipeline\GetSeriesVideos;
use Spectator\Processing\Series\Pipeline\SaveCreator;
use Spectator\Processing\Series\Pipeline\SaveSeries;
use Spectator\Processing\Series\Pipeline\SaveVideos;

class Populate extends Processor implements ProcessorInterface
{
    use DispatchesJobs;

    /**
     * @var Series
     */
    private $series;

    public function __construct(Series $series)
    {
        parent::__construct("seriespopulate");
        $this->series = $series;
    }

    public function execute()
    {
        $series = $this->series
            ->doesntHave('videos')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->log("Populating " . $series->count() . " series.");

        $series->each(function($model) {
            $data = collect([
                'config' => [
                    'mode' => 'new'
                ],
                'series' => $model
            ]);

            $this->dispatch(new Asyncify($data, $this->getPipeline()));
        });
    }

    public function getPipeline()
    {
        $pipeline = (new Pipeline)
            ->pipe(App::make(GetSeriesCreator::class))
            ->pipe(App::make(GetSeriesVideos::class))
            ->pipe(App::make(SaveCreator::class))
            ->pipe(App::make(SaveVideos::class))
            ->pipe(App::make(SaveSeries::class));

        return $pipeline;
    }
}