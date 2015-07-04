<?php

namespace Spectator\Services\Youtube;

use Spectator\Game;

class SearchService {

    public function getSearchQueryForGame(Game $game)
    {
        return $game->title . ' lets play';
    }
}