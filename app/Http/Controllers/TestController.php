<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;

use Spectator\Http\Requests;
use Spectator\Http\Controllers\Controller;

use Spectator\Repositories\GameRepository;
use Spectator\Repositories\YoutubeRepository;
use Spectator\Services\Youtube\YoutubeServiceManager;

class TestController extends Controller
{

    private $game;
    private $manager;
    private $youtubeRepo;

    public function __construct(YoutubeServiceManager $manager, GameRepository $game, YoutubeRepository $youtubeRepo)
    {
        $this->game = $game;
        $this->manager = $manager;
        $this->youtubeRepo = $youtubeRepo;
    }

    public function getGameContent($gameId)
    {
        $game = $this->game->get($gameId);
        $data = $this->manager->searchYoutubeContent($game->title . " lets play");
        $this->youtubeRepo->saveAll($data, $game);
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
