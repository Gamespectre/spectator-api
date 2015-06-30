<?php

namespace Spectator\Lib\Traits;

use Google_Client;

trait YoutubeClientTrait {

	public function createGoogleClient() {
		$client = new Google_Client();
		$client->setApplicationName('Spectator app');
		$client->setDeveloperKey(env('YOUTUBE_API_KEY'));

		return $client;
	}
}
