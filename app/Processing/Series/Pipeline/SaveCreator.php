<?php

namespace Spectator\Processing\Series\Pipeline;

use Illuminate\Support\Collection;
use League\Pipeline\StageInterface;

class SaveCreator implements StageInterface
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
        $creator = $payload->get('creator');

        $creator->persist();
        $creator->relatesToGame($game);

        $series->creator()->associate($creator->model);

        $creator->model->touch();

        return $payload;
    }
}