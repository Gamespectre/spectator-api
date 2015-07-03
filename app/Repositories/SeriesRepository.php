<?php

namespace Spectator\Repositories;

use Spectator\Video;
use Spectator\Series;
use Spectator\Creator;
use Spectator\Interfaces\RepositoryInterface;

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

	public function getSeriesByPlaylistId($playlistid)
	{
		$series = $this->series->where('playlist_id', $playlistid);
		return $series->with('videos.creator')->first();
	}

	public function get($id)
	{
		$series = Series::findOrFail($id)->load('videos.creator')->get();
		return $series->first();
	}

}