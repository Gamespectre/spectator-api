<?php

namespace Spectator\Sources;

use Spectator\Interfaces\SourceInterface;
use Spectator\Traits\GiantBombClientTrait;

class GiantBombSource {

	use GiantBombClientTrait;

	public function get($query) {
		$client = $this->createGiantBombClient();

        $response = $client->get('game/3030-' . $query, [
			'query' => $client->getConfig('query')
		]);

		$games = [];

		if($response->getStatusCode() === 200) {
			$games = json_decode($response->getBody(), true)['results'];
		}

		return $games;
	}

	public function search($query) {
		$client = $this->createGiantBombClient();
		$response = $client->get('search', [
            'query' => array_merge($client->getConfig('query'), [
                'query' => urlencode($query),
                'resources' => 'game',
                'resource_type' => 'game',
                'limit' => 10
            ])
		]);

		$games = [];

		if($response->getStatusCode() === 200) {
            $games = json_decode($response->getBody(), true)['results'];
		}

		return $games;
	}

}
