<?php

namespace Spectator\Lib\Repositories;

use Spectator\Game;
use Spectator\Lib\Interfaces\RepositoryInterface;
use Carbon\Carbon;

class GameRepository implements RepositoryInterface {

	public function __construct() {}

	public function getAll() {
		return Game::all();
	}

	public function get($id, $loadRelated = true) {
		$game = Game::find($id);

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

	public function createModel($data) {
		$properties = [
			'title' => $data->getName(),
			'api_id' => $data->getId(),
			'description' => $data->getDeck(),
			'franchise' => $data->getFranchises()[0]['name'],
			'year' => Carbon::parse($data->getOriginalReleaseDate())->year,
			'rating' => $data->getOriginalGameRating()[0]['name'],
			'image_url' => $data->getImage()['super_url'],
		];

		$model = Game::firstOrCreate($properties);
		return $model;
	}
}