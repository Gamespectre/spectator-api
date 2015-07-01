<?php

namespace Spectator\Lib\Repositories;

use Spectator\Video;
use Spectator\Series;
use Spectator\Creator;
use Spectator\Lib\Interfaces\RepositoryInterface;
use Carbon\Carbon;

class VideoRepository implements RepositoryInterface {

	private $game;

	public function __construct(GameRepository $gameRepo) {
		$this->game = $gameRepo;
	}

	public function getAll()
	{
		return Video::with('series', 'creator', 'game')->get();
	}

	public function getVideosByGame($gameid)
	{
		$game = $this->game->get($gameid);
		return $game->videos()->with('series', 'creator')->get();
	}

	public function get($id)
	{
		$video = Video::findOrFail($id)->load('series', 'creator', 'game')->get();
		return $video->first();
	}

	public function createModel($data)
	{
		$model = Video::where('video_id', $data['video_id'])->first();

		if(!is_null($model)) {
			return $model;
		}

		$props = [
			'title' => $data['title'],
			'video_id' => $data['video_id'],
			'description' => $data['description'],
			'image_url' => $data['image_url'],
			'published_at' => Carbon::parse($data['published_at']),
		];

		$model = Video::create($props);
		return $model;
	}
}