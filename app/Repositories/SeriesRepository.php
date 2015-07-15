<?php

namespace Spectator\Repositories;

use Spectator\Interfaces\RepositoryInterface;
use Spectator\Series;

class SeriesRepository implements RepositoryInterface {

	private $game;

	public function __construct(GameRepository $gameRepo) {
		$this->game = $gameRepo;
	}

	public function getAll($perPage = 20)
	{
		return Series::with('videos', 'game')->paginate($perPage);
	}

	public function getVideosInSeries($id, $perPage)
	{
        $series = Series::where('id', $id)->with('game', 'creator', 'videos')->first();
        return $series->videos()->with('creator', 'game')->paginate($perPage);
	}

	public function getGameOfSeries($seriesId)
	{
		$series = Series::where('id', $seriesId)->with('game', 'creator')->first();
		return $series->game()->with('series', 'creators', 'videos')->first();
	}

    public function getCreatorOfSeries($seriesId)
    {
        $series = Series::where('id', $seriesId)->with('creator.series', 'creator.games', 'creator.videos')->first();
        return $series->creator()->with('series', 'games', 'videos')->first();
    }

	public function getSeriesByPlaylistId($playlistid)
	{
		$series = $this->series->where('playlist_id', $playlistid);
		return $series->with('videos', 'creator')->first();
	}

	public function get($id)
	{
		$series = Series::findOrFail($id)->load('videos', 'creator')->get();
		return $series->first();
	}

}