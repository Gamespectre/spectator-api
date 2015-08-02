<?php

namespace Spectator\Processing\Games\Pipeline;

use League\Pipeline\StageInterface;
use Spectator\Services\Youtube\PlaylistService;
use Spectator\Services\Youtube\SearchService;

class SearchSeriesForGame implements StageInterface
{
    /**
     * @var SearchService
     */
    private $search;
    /**
     * @var PlaylistService
     */
    private $playlist;

    public function __construct(SearchService $search, PlaylistService $playlist)
    {
        $this->search = $search;
        $this->playlist = $playlist;
    }
    /**
     * Process the payload.
     * @param mixed $payload
     * @return mixed
     */
    public function process($payload)
    {
        $dataCollection = $payload;

        if($dataCollection->has('game')) {
            $game = $payload->get('game');

            dump("Searching series for " . $game->title);

            $query = $this->search->getSearchQueryForGame($game);
            $playlists = $this->playlist->searchPlaylists($query, 1);

            $dataCollection->put('series', $playlists->first());
        }

        return $dataCollection;
    }
}