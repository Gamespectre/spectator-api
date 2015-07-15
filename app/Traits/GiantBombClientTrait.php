<?php

namespace Spectator\Traits;

use GuzzleHttp\Client;

trait GiantBombClientTrait {

	public function createGiantBombClient() {
        $client = new Client([
            'base_uri' => 'http://www.giantbomb.com/api/',
            'query' => [
                'api_key' => getenv('GIANTBOMB_API_KEY'),
                'format' => 'json',
                'field_list' => 'name,id,deck,franchises,original_release_date,original_game_rating,image'
            ]
        ]);

		return $client;
	}
}
