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

	public function createModel($data)
	{
		$model = Series::where('playlist_id', $data['playlist_id'])->first();

		if(!is_null($model)) {
			return $model;
		}

		$props = [
			'name' => $data['name'],
			'playlist_id' => $data['playlist_id'],
			'published_at' => $data['published_at'],
		];

		$model = Series::create($props);
		return $model;
	}

}