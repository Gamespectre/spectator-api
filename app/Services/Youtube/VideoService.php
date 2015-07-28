<?php

namespace Spectator\Services\Youtube;

use Cache;
use Illuminate\Support\Collection;
use Spectator\Datamodels\PlaylistItem;
use Spectator\Datamodels\Video;
use Spectator\Services\ApiService;
use Spectator\Sources\YoutubeSource;

set_time_limit(0);

class VideoService extends ApiService {

	private $source;
    protected $event = VideosRetrieved::class;

	public $actions = [
		'playlists' => 'getVideosForPlaylists',
		'search' => 'searchVideos',
		'add' => 'getVideo'
	];

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

    public function searchVideos($query, $force = false)
    {
        $params = [
            'type' => 'video',
            'maxResults' => 50,
            'part' => 'id, snippet',
            'videoEmbeddable' => 'true',
            'videoLicense' => 'any',
            'q' => $query
        ];

        $cacheKey = $query . ':videos';

        if($force === true) {
            Cache::forget($cacheKey);
        }

        $results = Cache::remember($cacheKey, env('API_CACHE_MINUTES', 720), function() use ($params) {
            return $this->source->search($params);
        });

        return Video::createData(collect($results[$results['collection_key']]));
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
            ->map(function($item) {
				return $item->id;
			})
			->unique()
			->each(function($item) use (&$videos, $force) {
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

		return Video::createData(collect($results['items']));
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

		return Video::createData($results['items']);
	}

	public function getVideosInPlaylist($playlistId, $force = false)
	{
		$pager = new YoutubeApiPager(50);
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

			$apiData = Cache::remember($cacheKey, env('API_CACHE_MINUTES', 60), function() use ($params) {
				return $this->source->getVideosInSeries($params);
			});

			$results = PlaylistItem::createData(collect($apiData['items']))->merge($results->all());

			return $apiData;
		});

		return $results;
	}

}