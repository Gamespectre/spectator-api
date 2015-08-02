<?php

namespace Spectator\Processing\Series\Pipeline;

use Illuminate\Support\Collection;
use League\Pipeline\StageInterface;

class SaveSeriesData implements StageInterface
{
    /**
     * Process the payload.
     * @param Collection $payload
     * @return Collection
     */
    public function process($payload)
    {
        $series = $payload->pull('series');
        $series->persist();

        if($payload->has('game')) {
            $game = $payload->get('game');
            $series->relatesToGame($game);
        }

        $payload->put('series', $series->model);

        return $payload;
    }
}