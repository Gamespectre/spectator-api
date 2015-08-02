<?php

namespace Spectator\Processing\Series;

use App;
use Illuminate\Foundation\Bus\DispatchesJobs;
use League\Pipeline\Pipeline;
use Spectator\Interfaces\ProcessorInterface;
use Spectator\Jobs\Asyncify;
use Spectator\Processing\Processor;
use Spectator\Processing\Series\Pipeline\GetSeriesVideos;
use Spectator\Processing\Series\Pipeline\SaveSeries;
use Spectator\Processing\Series\Pipeline\SaveVideos;
use Spectator\Series;

class Update extends Processor implements ProcessorInterface
{
    use DispatchesJobs;

    /**
     * @var Series
     */
    private $series;

    public function __construct(Series $series)
    {
        parent::__construct('seriesupdate');
        $this->series = $series;
    }

    public function execute()
    {
        $series = $this->series
            ->orderBy('updated_at', 'asc')
            ->take(10)
            ->get();

        $this->log("Updating videos of " . $series->count() . " series.");

        $series->each(function($model) {
            $data = collect([
                'config' => [
                    'mode' => 'update'
                ],
                'series' => $model
            ]);

            $this->dispatch(new Asyncify($data, $this->getPipeline()));
        });
    }

    public function getPipeline()
    {
        $pipeline = (new Pipeline)
            ->pipe(App::make(GetSeriesVideos::class))
            ->pipe(App::make(SaveVideos::class))
            ->pipe(App::make(SaveSeries::class));

        return $pipeline;
    }
}