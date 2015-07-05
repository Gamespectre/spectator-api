<?php

namespace Spectator\Services\Youtube;

use Cache;
use Spectator\Datamodels\Video;
use Spectator\Events\Api\Youtube\Videos\VideosRetrieved;
use Spectator\Interfaces\PackageHandler;
use Spectator\Services\ApiService;
use Spectator\Sources\YoutubeSource;
use Illuminate\Support\Collection;
use Spectator\Traits\PackagesData;

set_time_limit(0);

class VideoService extends ApiService implements PackageHandler {

	use PackagesData;

	private $source;
    protected $event = VideosRetrieved::class;

	public $actions = [
		'playlists' => 'getVideosForPlaylists'
	];

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

    public function subscribe($events)
    {
        $events->listen(
            'Spectator\Events\Api\Youtube\Playlists\PlaylistsRetrieved',
            'video@packEventPackage'
        );
    }

	public function getVideos(Collection $videoIds, $force = false)
	{
		return $this->getFromIds($videoIds, 'getVideo', $force);
	}

	public function getVideosForPlaylists(Collection $playlists, $force = false)
	{
		if($playlists->isEmpty()) {
			return $playlists;
		}

		$videos = collect([]);

		$playlists
            ->map(function($item, $key) {
				return $item->id;
			})
			->unique()
			->each(function($item, $key) use (&$videos, $force) {
				$videos = $this->getVideosInPlaylist($item, $force)->merge($videos->all());
			});

		return $videos;
	}

	public function getVideosBatch(Collection $videoIds, $cacheKey, $force = false)
	{
		$videoIdsString = $videoIds->implode(',');

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

		return Video::createFromCollection(collect($results['items']));
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

		return Video::createFromItem($results['items']);
	}

	public function getVideosInPlaylist($playlistId, $force = false)
	{
		$pager = new YoutubeApiPager(50, 50, false);
		$results = collect([]);

		$pager->page(function($pager) use (&$results, $playlistId, $force) {

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

			$plItems = collect($playlistItemResults['items']);
			$batchCacheKey = $playlistId . ':videos:batch:' . $pager->getPage();

			$videoIds = $plItems
				->map(function($item, $key) {
					return $item->snippet->resourceId->videoId;
				})->unique();

			$results = $this->getVideosBatch($videoIds, $batchCacheKey)
				->each(function($item, $key) use ($playlistId) {
					$item->playlist = $playlistId;
				})->merge($results->all());

			return $playlistItemResults;
		});

		return $results;
	}

}