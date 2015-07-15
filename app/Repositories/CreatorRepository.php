<?php

namespace Spectator\Repositories;

use Spectator\Creator;
use Spectator\Interfaces\RepositoryInterface;

class CreatorRepository implements RepositoryInterface {

	public function __construct() {

	}

	public function getAll($perPage = 20)
	{
		return Creator::with('games', 'series')->paginate($perPage);
	}

    public function getGamesByCreator($creatorId)
    {
        $creator = Creator::where('id', $creatorId)->with('games')->first();
        return $creator->games()->with('creators', 'series')->get();
    }

    public function getSeriesByCreator($creatorId, $perPage)
    {
        $creator = Creator::where('id', $creatorId)->with('series')->first();
        return $creator->series()->with('videos', 'game')->paginate($perPage);
    }

    public function getVideosByCreator($creatorId, $perPage)
    {
        $creator = Creator::where('id', $creatorId)->with('videos')->first();
        return $creator->videos()->with('series', 'game')->paginate($perPage);
    }

	public function get($id)
	{
		$creator = Creator::findOrFail($id)->load('videos')->get();
		return $creator->first();
	}
}