<?php

namespace Spectator\Sources;

use Spectator\Interfaces\SourceInterface;
use Spectator\Traits\GiantBombClientTrait;

class GiantBombSource {

	use GiantBombClientTrait;

	public function get($query) {
		$client = $this->createGiantBombClient();
		$response = $client->getGame([
			'id' => $query
		]);

		$games = [];

		if($response->getStatusCode() === 1) {
			$games = $response->getResults();
		}

		return $games;
	}

}
