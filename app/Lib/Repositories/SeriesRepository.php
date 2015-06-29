<?php

namespace Spectator\Lib\Repositories;

use Spectator\Video;
use Spectator\Series;
use Spectator\Creator;
use Spectator\Lib\Interfaces\RepositoryInterface;

class SeriesRepository implements RepositoryInterface {

	private $game;

	public function __construct(GameRepository $gameRepo) {
		$this->game = $gameRepo;
	}

	public function getAll()
	{
		return Series::with('videos', 'game')->get();
	}

	public function getSeriesByGame($gameid)
	{
		$game = $this->game->get($gameid);
		return $game->series()->with('videos.creator')->get();
	}

	public function get($id)
	{
		$series = Series::findOrFail($id)->load('videos.creator')->get();
		return $series->first();
	}

	public function createModel($data)
	{
		//
	}

}