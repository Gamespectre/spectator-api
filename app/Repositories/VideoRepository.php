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
}