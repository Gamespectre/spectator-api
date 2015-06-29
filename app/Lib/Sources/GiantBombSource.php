<?php

namespace Spectator\Lib\Sources;

use Spectator\Lib\Interfaces\SourceInterface;

class GiantBombSource implements SourceInterface {

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
