<?php

namespace Spectator\Services\Youtube;

use Cache;
use Spectator\Traits\YoutubeDataTransformerTrait;
use Spectator\Sources\YoutubeSource;

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

	public function getVideosBatch(array $videoIds, $cacheKey, $force = false)
	{
		$videoIdsString = implode($videoIds, ',');

		$params = [
			'maxResults' => 50,
			'id' => $videoIdsString
		];

		if($force === true) {
			Cache::forget($cacheKey);
		}

		$results = Cache::rememberForever($cacheKey, function() use ($params) {
			return $this->source->getVideo($params);
		});

		return $results['items'];
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
		$pager = new YoutubeApiPager(50);
		$cumulativeResults = [];

		$pager->page(function($pager) use (&$cumulativeResults, $playlistId, $force) {

			$params = [
				'maxResults' => $pager->getChunk(),
				'playlistId' => $playlistId,
				'pageToken' => $pager->getToken()
			];

			$cacheKey = $playlistId . ':videos:' . $pager->getPage();

			if($force === true) {
				Cache::forget($cacheKey);
			}

			$playlistItemResults = Cache::remember($cacheKey, env('API_CACHE_MINUTES', 60), function() use ($params) {
				return $this->source->getVideosInSeries($params);
			});

			if(!empty($playlistItemResults['items'])) {
				$videoIds = [];

				foreach($playlistItemResults as $plItem) {
					$videoIds[] = $plItem->snippet->resourceId->videoId;
				}

				$batchCacheKey = $playlistId . ':videos:batch:' . $pager->getPage();

				$videos = array_map(function($video) use ($playlistId) {
					$video->snippet->playlistId = $playlistId;
					return $video;
				}, $this->getVideosBatch($videoIds, $batchCacheKey));

				$cumulativeResults = array_merge(
					$cumulativeResults,
					$videos
				);
			}

			return $playlistItemResults;
		});

		return $cumulativeResults;
	}

}