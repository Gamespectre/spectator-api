<?php

namespace Spectator\Services\Youtube;

use Cache;
use Spectator\Traits\YoutubeDataTransformerTrait;
use Spectator\Sources\YoutubeSource;

set_time_limit(0);

class PlaylistService {

	use YoutubeDataTransformerTrait;

	public $playlists = [];
	private $source;

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

	public function getPlaylists(array $playlistIds, $force = false)
	{
		if(count($playlistIds) === 0) {
			return [];
		}

		$cumulativeResults = [];

		$uniqueIds = array_unique($playlistIds, SORT_REGULAR);

		foreach($uniqueIds as $playlistId)
		{
			$cumulativeResults = array_merge($cumulativeResults, $this->getPlaylist($playlistId, $force));
		}

		return $this->createData($cumulativeResults, 'createPlaylistItem');
	}

	public function updatePlaylist($playlistId)
	{
		return $this->getPlaylist($playlistId, true);
	}

	public function getPlaylist($playlistId, $force = false)
	{
		$params = [
			'part' => 'id, snippet',
			'id' => $playlistId
		];

		$cacheKey = $playlistId;

		if($force === true) {
			Cache::forget($cacheKey);
		}

		$results = Cache::remember($cacheKey, env('API_CACHE_MINUTES', 60), function() use ($params) {
			return $this->source->getSeries($params);
		});

		return $results['items'];
	}

	public function searchPlaylists($query, $playlistsToGet, $force = false)
	{
		$page = 0;
		$fetched = 0;
		$pageToken = null;
		$resultsToGet = $playlistsToGet > 0 ? $playlistsToGet : 10;
		$resultsPerCall = $playlistsToGet < 50 ? $playlistsToGet : 50;
		$cumulativeResults = [];

		for($i = 0; $i < $resultsToGet; $i += $resultsPerCall)
		{
			$params = [
				'type' => 'playlist',
				'maxResults' => $resultsPerCall,
				'part' => 'id, snippet',
				'pageToken' => $pageToken,
				'q' => $query
			];

			$cacheKey = $query . ':playlists:' . $page;

			if($force === true) {
				Cache::forget($cacheKey);
			}

			$results = Cache::remember($cacheKey, env('API_CACHE_MINUTES', 720), function() use ($params) {
				return $this->source->search($params);
			});

			$totalResults = $results['pageInfo']['totalResults'];
			$fetched += $results['pageInfo']['resultsPerPage'];
			$stuffLeft = $totalResults - $fetched;
			$resultsPerCall = $stuffLeft < $resultsPerCall ? $stuffLeft : $resultsPerCall;

			$pageToken = $results['nextPageToken'];

			$cumulativeResults = array_merge($cumulativeResults, $results['items']);

			$page++;

			if($stuffLeft <= 0 || is_null($pageToken)) {
				break;
			}
		}

		return $this->createData($cumulativeResults, 'createPlaylistItem');
	}

}