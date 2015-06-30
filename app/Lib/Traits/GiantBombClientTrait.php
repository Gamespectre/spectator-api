<?php

namespace Spectator\Lib\Traits;

use GiantBomb\Client\GiantBombClient;

trait GiantBombClientTrait {

	public function createGiantBombClient() {
		$client = GiantBombClient::factory([
			'apiKey' => env('GIANTBOMB_API_KEY')
		]);
		return $client;
	}
}
