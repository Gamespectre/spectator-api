<?php

namespace Spectator\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spectator\Game;
use Spectator\Interfaces\RepositoryInterface;
use Spectator\Services\App\Package;

class GameRepository implements RepositoryInterface {

	public function __construct() {}

	public function getAll() {
		return Game::all();
	}

	public function get($id, $loadRelated = true) {
		$game = Game::find((int) $id);

		if($loadRelated && $game !== null) {
			$game->load('videos.creator', 'series');
		}

		return $game;
	}

	public function getByName($name, $loadRelated = true) {
		$game = Game::where('title', 'like', $name . '%')->first();

		if($loadRelated && $game !== null) {
			$game->load('videos.creator', 'series');
		}

		return $game;
	}

	public function getByApiId($id, $loadRelated = true) {
		$game = Game::where('api_id', $id)->first();

		if($loadRelated && $game !== null) {
			$game->load('videos.creator', 'series');
		}

		return $game;
	}

    public function saveGames(Collection $gameData)
    {
        $gameData->each(function($data, $key) {
            $data->persist();
        });
    }
}