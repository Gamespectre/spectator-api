<?php

namespace Spectator\Repositories;

use Spectator\Interfaces\RepositoryInterface;
use Spectator\Video;

class VideoRepository implements RepositoryInterface {

	private $game;

	public function __construct(GameRepository $gameRepo) {
		$this->game = $gameRepo;
	}

	public function getAll()
	{
		return Video::with('series', 'creator', 'game')->get();
	}

	public function getSeriesByVideo($videoId, $perPage)
	{
		$series = Video::where('id', $videoId)->with('creator', 'series')->first();
        return $series->series()->with('creator', 'game')->paginate($perPage);
	}

    public function getGameByVideo($videoId)
    {
        $game = Video::where('id', $videoId)->with('game')->first();
        return $game->game()->with('creators', 'series')->first();
    }

    public function getCreatorByVideo($videoId)
    {
        $creator = Video::where('id', $videoId)->with('creator')->first();
        return $creator->creator()->with('games', 'series')->first();
    }

	public function get($id)
	{
		$video = Video::findOrFail($id)->load('series', 'creator', 'game')->get();
		return $video->first();
	}
}