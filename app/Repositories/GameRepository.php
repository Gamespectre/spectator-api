<?php

namespace Spectator\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spectator\Game;
use Spectator\Interfaces\RepositoryInterface;
use Spectator\Services\App\Package;

class GameRepository implements RepositoryInterface {

	public function __construct() {}

	public function getAll($perPage = 20) {

		if($perPage !== false) {
            return Game::paginate($perPage);
        }

        return Game::all();
	}

	public function get($identifier) {
		$game = null;

        if(is_numeric($identifier)) {
            $game = Game::where('id', $identifier)->orWhere('api_id', $identifier)
                    ->with('videos', 'creators', 'series')->first();
        }
        else {
            $game = $this->getByName($identifier);
        }

		return $game;
	}

	public function getVideosByGame($id, $perPage = 10)
	{
		$game = $this->get($id);

        if($perPage !== false) {
            return $game->videos()->paginate($perPage);
        }

        return $game->videos;
	}

    public function getSeriesByGame($id, $perPage = 10)
    {
        $game = $this->get($id);

        if($perPage !== false) {
            return $game->series()->with('videos', 'creator')->paginate($perPage);
        }

        return $game->series;
    }

    public function getCreatorsByGame($id, $perPage = 10)
    {
        $game = $this->get($id);

        if($perPage !== false) {
            return $game->creators()->with('videos', 'series')->paginate($perPage);
        }

        return $game->series;
    }

	public function getByName($name) {
		$game = Game::where('title', 'like', '%' . $name . '%')
                ->with('videos', 'creators', 'series')->first();

		return $game;
	}

	public function getByApiId($id) {
		$game = Game::where('api_id', $id)
                ->with('videos', 'creators', 'series')->first();

		return $game;
	}

    public function saveGames(Collection $gameData)
    {
        $gameData->each(function($data, $key) {
            $data->persist();
        });
    }
}