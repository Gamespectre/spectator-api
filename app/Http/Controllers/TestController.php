<?php

namespace Spectator\Http\Controllers;

use Spectator\Events\Game\Search as GameSearch;
use Spectator\Events\Youtube\Search as YoutubeSearch;
use Spectator\Http\Requests;
use Spectator\Repositories\GameRepository;
use Spectator\Services\Youtube\YoutubeServiceManager;
use Spectator\Sources\GiantBombSource;

class TestController extends Controller
{
    /**
     * @var GameRepository
     */
    private $game;
    /**
     * @var GiantBombSource
     */
    private $source;

    public function __construct(GameRepository $game, GiantBombSource $source)
    {
        $this->game = $game;
        $this->source = $source;
    }

    public function getAddGame($query)
    {
        \Event::fire(new GameSearch([
            'gameApiId' => $query
        ]));
    }

    public function getGameContent($gameId)
    {
        $game = $this->game->get($gameId);

        \Event::fire(new YoutubeSearch([
            'game' => $game
        ]));
    }

    public function getAddPlaylist($playlistId, $gameId)
    {
        $game = $this->game->get($gameId);
        $this->manager->addPlaylists([$playlistId], $game);
    }

    public function getAddVideo($videoId, $gameId)
    {
        $game = $this->game->get($gameId);
        $this->manager->addVideo($videoId, $game);
    }
}
