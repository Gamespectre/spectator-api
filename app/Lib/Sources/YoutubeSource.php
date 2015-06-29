<?php

namespace Spectator\Lib\Sources;

use Spectator\Lib\Interfaces\SourceInterface;
use Google_Service_YouTube;

class YoutubeSource implements SourceInterface {

	use YoutubeClientTrait;

	public function get($query, $options = [])
	{
		$client = $this->createGoogleClient();
		$youtube = new Google_Service_YouTube($client);

		$queryParams = [
			'type' => isset($options['type']) ? $options['type'] : 'playlist',
			'maxResults' => isset($options['limit']) ? $options['limit'] : 50,
			'q' => $query
		];

		if(isset($options['pageToken'])) {
			$queryParams['pageToken'] = $options['pageToken'];
		}

		if($queryParams['type'] === 'video') {
			$queryParams['videoEmbeddable'] = "true";
		}

		$searchResponse = $youtube->search->listSearch('snippet', $queryParams);

		return $searchResponse;
	}

	public function getPlaylistVideos($playlistId)
	{
		$client = $this->createGoogleClient();
		$youtube = new Google_Service_YouTube($client);

		$searchResponse = $youtube->playlistItems->list('snippet', [
			'playlistId' => $playlistId
		]);

		return $searchResponse;
	}

	public function getCreatorForResource($resource)
	{
		$client = $this->createGoogleClient();
		$youtube = new Google_Service_YouTube($client);

		$searchResponse = $youtube->playlistItems->list('snippet', [
			'playlistId' => $playlistId
		]);

		return $searchResponse['items'];
	}

}
