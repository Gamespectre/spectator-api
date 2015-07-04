<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;

use Spectator\Http\Requests;
use Spectator\Http\Controllers\Controller;
use Spectator\Events\Api\Youtube\PlaylistSearch;
use Spectator\Services\App\YoutubePackage;
use Spectator\Repositories\GameRepository;
use Spectator\Repositories\YoutubeRepository;
use Spectator\Services\Youtube\YoutubeServiceManager;

class TestController extends Controller
{
    /**
     * @var GameRepository
     */
    private $game;

    public function __construct(GameRepository $game)
    {
        $this->game = $game;
    }

    public function getGameContent($gameId)
    {
        $game = $this->game->get($gameId);

        \Event::fire(new PlaylistSearch([
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
