<?php

namespace Spectator\Processing\Series\Pipeline;

use Illuminate\Support\Collection;
use League\Pipeline\StageInterface;

class SaveSeries implements StageInterface
{
    /**
     * Process the payload.
     * @param Collection $payload
     * @return Collection
     */
    public function process($payload)
    {
        $series = $payload->get('series');

        $series->touch();
        $series->save();

        return $payload;
    }
}