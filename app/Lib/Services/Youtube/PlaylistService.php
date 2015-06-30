<?php

namespace Spectator\Lib\Services\Youtube;

use Cache;
use Spectator\Lib\Traits\YoutubeDataTransformerTrait;
use Spectator\Lib\Sources\YoutubeSource;

set_time_limit(0);

class PlaylistService {

	use YoutubeDataTransformerTrait;

	public $playlists = [];
	private $source;

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

	public function searchPlaylists($query, $force = false)
	{
		$page = 0;
		$fetched = 0;
		$pageToken = null;
		$resultsToGet = 200;
		$resultsPerCall = 50;
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

			$results = Cache::rememberForever($cacheKey, function() use ($params) {
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

		$this->playlists = $this->createData($cumulativeResults, 'createPlaylistItem');

		return $this->playlists;
	}

}