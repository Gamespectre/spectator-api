<?php

namespace Spectator\Lib\Services\Youtube;

use Cache;
use Spectator\Lib\Traits\YoutubeDataTransformerTrait;
use Spectator\Lib\Sources\YoutubeSource;

set_time_limit(0);

class VideoService {

	use YoutubeDataTransformerTrait;

	public $videos = [];
	private $source;

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

	public function getPlaylistsVideos($playlists, $force = false)
	{
		$cumulativeResults = [];

		foreach($playlists as $playlist)
		{
			$playlistId = $playlist['playlist_id'];

			$cumulativeResults = array_merge($cumulativeResults, $this->getVideosInPlaylist($playlistId, $force));
		}

		$this->videos = $this->createData($cumulativeResults, 'createVideoItem');

		return $this->videos;
	}

	public function getVideosInPlaylist($playlistId, $force = false)
	{
		$page = 0;
		$fetched = 0;
		$pageToken = '';
		$resultsPerCall = 50;
		$totalResults = 9999; // Initially high
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

			$results = Cache::rememberForever($cacheKey, function() use ($params) {
				return $this->source->getVideosInSeries($params);
			});

			$totalResults = (int) $results['pageInfo']['totalResults'];
			$fetched += (int) $results['pageInfo']['resultsPerPage'];
			$stuffLeft = $totalResults - $fetched;
			$resultsPerCall = $stuffLeft < $resultsPerCall ? $stuffLeft : $resultsPerCall;

			$pageToken = $results['nextPageToken'];

			$cumulativeResults = array_merge($cumulativeResults, $results['items']);

			$page++;

			if($stuffLeft <= 0 || is_null($pageToken)) {
				break;
			}
		}

		return $cumulativeResults;
	}

}