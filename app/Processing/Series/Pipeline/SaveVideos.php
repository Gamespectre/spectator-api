<?php

namespace Spectator\Processing\Series\Pipeline;

use Illuminate\Support\Collection;
use League\Pipeline\StageInterface;
use Spectator\Datamodels\Video;

class SaveVideos implements StageInterface
{
    /**
     * Process the payload.
     * @param Collection $payload
     * @return Collection
     */
    public function process($payload)
    {
        $series = $payload->get('series');
        $game = $series->game;
        $creator = $payload->has('creator') ? $payload->get('creator')->model : $series->creator;
        $videos = $payload->get('videos');

        if($payload->get('config')['mode'] === 'update') {
            $videos = $videos->reject(function($video) {
                return $video->isPersisted();
            });
        }

        $videos->each(function($video) use ($creator, $series, $game) {
            $video->persist();
            $video->relatesToGame($game);
            $video->relatesToSeries($series);
            $video->relatesToCreator($creator);

            $video->model->touch();
        });

        return $payload;
    }
}