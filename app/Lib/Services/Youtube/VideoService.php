<?php

namespace Spectator\Lib\Services\Youtube;

use Cache;
use Spectator\Lib\Traits\YoutubeDataTransformerTrait;
use Spectator\Lib\Sources\YoutubeSource;

set_time_limit(0);

class VideoService {

	use YoutubeDataTransformerTrait;

	private $source;

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

	public function getVideosForPlaylists(array $playlistIds, $force = false)
	{
		if(count($playlistIds) === 0) {
			return [];
		}

		$cumulativeResults = [];

		$uniqueIds = array_unique($playlistIds, SORT_REGULAR);

		foreach($uniqueIds as $playlistId)
		{
			$cumulativeResults = array_merge($cumulativeResults, $this->getVideosInPlaylist($playlistId, $force));
		}

		return $this->createData($cumulativeResults, 'createVideoItem');
	}

	public function getVideos(array $videoIds, $force = false)
	{
		if(count($videoIds) === 0) {
			return [];
		}

		$cumulativeResults = [];

		$uniqueIds = array_unique($videoIds, SORT_REGULAR);

		foreach($uniqueIds as $videoId)
		{
			$cumulativeResults = array_merge($cumulativeResults, $this->getVideo($videoId, $force));
		}

		return $this->createData($cumulativeResults, 'createVideoItem');
	}

	public function updateVideo($videoId)
	{
		return $this->getVideo($videoId, true);
	}

	public function getVideo($videoId, $force = false)
	{
		$params = [
			'maxResults' => 1,
			'id' => $videoId
		];

		$cacheKey = $videoId;

		if($force === true) {
			Cache::forget($cacheKey);
		}

		$results = Cache::rememberForever($cacheKey, function() use ($params) {
			return $this->source->getVideo($params);
		});

		return $results['items'];
	}

	public function getVideosInPlaylist($playlistId, $force = false)
	{
		$page = 0;
		$fetched = 0;
		$pageToken = '';
		$resultsPerCall = 50;
		$totalResults = 9999;
		$cumulativeResults = [];

		for($i = 0; $i < $totalResults; $i += $resultsPerCall)
		{
			$params = [
				'maxResults' => $resultsPerCall,
				'playlistId' => $playlistId,
				'pageToken' => $pageToken
			];

			$cacheKey = $playlistId . ':videos:' . $page;

			if($force === true) {
				Cache::forget($cacheKey);
			}

			$playlistItemResults = Cache::remember($cacheKey, env('API_CACHE_MINUTES', 60), function() use ($params) {
				return $this->source->getVideosInSeries($params);
			});

			$videoResults = [];

			foreach($playlistItemResults as $plItem) {
				$videoResult = $this->getVideo($plItem->snippet->resourceId->videoId, $force);

				if(isset($videoResult[0])) {
					$videoResult[0]->snippet->playlistId = $plItem->snippet->playlistId;
				}

				$videoResults = array_merge($videoResults, $videoResult);
			}

			$totalResults = (int) $playlistItemResults['pageInfo']['totalResults'];
			$fetched += (int) $playlistItemResults['pageInfo']['resultsPerPage'];
			$stuffLeft = $totalResults - $fetched;
			$resultsPerCall = $stuffLeft < $resultsPerCall ? $stuffLeft : $resultsPerCall;

			$pageToken = $playlistItemResults['nextPageToken'];

			$cumulativeResults = array_merge($cumulativeResults, $videoResults);

			$page++;

			if($stuffLeft <= 0 || is_null($pageToken)) {
				break;
			}
		}

		return $cumulativeResults;
	}

}