<?php

namespace Spectator\Lib\Services;

//use Spectator\Lib\Sources\YoutubeSource;
use Madcoda\Youtube;
use Cache;
use Google_Service_YouTube;
use Spectator\Lib\Sources\YoutubeClientTrait;

class YoutubeResourcesCreator {

	use YoutubeClientTrait;

	protected $source;
	private $videos = [];
	private $playlists = [];
	private $creators = [];
	private $chunk = 10;

	public function __construct()
	{
		$this->source = new Youtube(array('key' => env('YOUTUBE_API_KEY')));
	}

	/**
	 * Entry methods
	 */

	public function getYoutubeContent($initalQuery)
	{
		$this->getPlaylists($initalQuery);
		$this->getVideosInPlaylistQueue();
		$this->getVideoCreators();

		$data = [
			'videos' => $this->videos,
			'playlists' => $this->playlists,
			'creators' => $this->creators,
		];

		return (object) $data;
	}

	public function getPlaylists($query)
	{
		$apiData = Cache::rememberForever($query . ':playlists', function() use ($query) {

			$client = $this->createGoogleClient();
			$youtube = new Google_Service_YouTube($client);

			$fetched = 0;
			$pageToken = null;
			$resultsPerCall = 10;
			$cumulativeResults = [];

			for($i = 0; $i < $this->chunk; $i += $resultsPerCall)
			{
				$results = $youtube->search->listSearch('id, snippet', [
					'type' => 'playlist',
					'maxResults' => $resultsPerCall,
					'part' => 'id, snippet',
					'pageToken' => $pageToken,
					'q' => $query
				]);

				$totalResults = $results['pageInfo']['totalResults'];

				$fetched += $results['pageInfo']['resultsPerPage'];
				$stuffLeft = $totalResults - $fetched;

				$resultsPerCall = $stuffLeft < $resultsPerCall ? $stuffLeft : $resultsPerCall;

				$pageToken = !is_null($results['nextPageToken']) ? $results['nextPageToken'] : null;
				$cumulativeResults = array_merge($cumulativeResults, $results['items']);

				if($stuffLeft <= 0 || is_null($pageToken)) {
					break;
				}
			}

			return $cumulativeResults;
		});

		$playlists = [];

		foreach($apiData as $result) {
			$playlistData = $this->createPlaylistItem($result);
			$playlists[] = $playlistData;
		}

		$this->playlists = array_unique($playlists, SORT_REGULAR);

		return $this;
	}

	public function getVideosInPlaylistQueue()
	{
		$cumulativeResults = [];

		foreach($this->playlists as $playlist)
		{
			$playlistId = $playlist['id'];
			$apiData = Cache::rememberForever($playlistId . ':videos', function() use ($playlistId) {

				$client = $this->createGoogleClient();
				$youtube = new Google_Service_YouTube($client);

				$fetched = 0;
				$resultsPerCall = 10;
				$totalResults = 9999; // Initially high
				$pageToken = '';
				$apiData = [];

				for($i = 0; $i < $totalResults; $i += $resultsPerCall)
				{
					$results = $youtube->playlistItems->listPlaylistItems('id, snippet, contentDetails, status', [
						'maxResults' => $resultsPerCall,
						'playlistId' => $playlistId
					]);

					$totalResults = $results['pageInfo']['totalResults'];

					$fetched += $results['pageInfo']['resultsPerPage'];
					$stuffLeft = $totalResults - $fetched;

					$resultsPerCall = $stuffLeft < $resultsPerCall ? $stuffLeft : $resultsPerCall;

					$pageToken = $results['nextPageToken'];

					$apiData = array_merge($apiData, $results['items']);

					if($stuffLeft <= 0 || is_null($pageToken)) {
						break;
					}
				}

				return $apiData;
			});

			$cumulativeResults = array_merge($cumulativeResults, $apiData);
		}

		$videos = [];

		foreach($cumulativeResults as $result) {
			$videoData = $this->createVideoItem($result);
			$videos[] = $videoData;
		}

		$this->videos = array_unique($videos, SORT_REGULAR);

		return $this;
	}

	public function getVideoCreators()
	{
		$cumulativeResults = [];

		foreach($this->videos as $video) {

			$channelId = $video['channel'];
			$apiData = Cache::rememberForever($channelId, function() use ($channelId) {

				$client = $this->createGoogleClient();
				$youtube = new Google_Service_YouTube($client);

				$result = $youtube->channels->listChannels(
					'brandingSettings, snippet, id, statistics', [
					'id' => $channelId,
					'maxResults' => 50
				]);

				return $result['items'][0];
			});

			$cumulativeResults[] = $this->createCreatorItem($apiData);
		}

		$this->creators = array_unique($cumulativeResults, SORT_REGULAR);

		return $this;
	}

	/**
	 * Data processors
	 */

	private function createPlaylistItem($raw)
	{
		return [
			'id' => $raw['id']['playlistId'],
			'channel' => $raw['snippet']['channelId'],
			'title' => $raw['snippet']['title'],
			'timestamp' => $raw['snippet']['publishedAt'],
		];
	}

	private function createVideoItem($raw)
	{
		$data = $raw['snippet'];

		return [
			'id' => $data['resourceId']['videoId'],
			'channel' => $data['channelId'],
			'title' => $data['title'],
			'description' => $data['description'],
			'timestamp' => $data['publishedAt'],
		];
	}

	private function createCreatorItem($raw)
	{
		return [
			'channel_id' => $raw['id'],
			'name' => $raw['snippet']['title'],
			'subscribers' => $raw['statistics']['subscriberCount'],
			'description' => $raw['snippet']['description'],
			'birthday' => $raw['snippet']['publishedAt'],
			'avatar_url' => $raw['snippet']['thumbnails']['high']['url'],
			'image_url' => $raw['brandingSettings']['image']['bannerTabletExtraHdImageUrl'],
		];
	}

}