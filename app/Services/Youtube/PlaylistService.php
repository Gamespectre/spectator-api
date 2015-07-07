<?php

namespace Spectator\Services\Youtube;

use Cache;
use Illuminate\Support\Collection;
use Spectator\Datamodels\Playlist;
use Spectator\Events\Youtube\PlaylistsRetrieved;
use Spectator\Interfaces\PackageHandler;
use Spectator\Services\ApiService;
use Spectator\Sources\YoutubeSource;
use Spectator\Traits\PackagesData;

set_time_limit(0);

class PlaylistService extends ApiService implements PackageHandler {

    use PackagesData;

	private $source;
    protected $event = PlaylistsRetrieved::class;

    public $actions = [
        'search' => 'searchPlaylists'
    ];

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

	public function getPlaylists(Collection $playlistIds, $force = false)
	{
		$playlists = $this->getFromIds($playlistIds, 'getPlaylist', $force);
        return $playlists;
    }

	public function updatePlaylist($playlistId)
	{
		$playlist = $this->getPlaylist($playlistId, true);

        return $playlist;
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

		return Playlist::createFromItem($results['items']);
	}

	public function searchPlaylists($query, $playlistsToGet, $force = false)
	{
		$chunk = $playlistsToGet > 50 ? 50 : $playlistsToGet;
		$pager = new YoutubeApiPager($playlistsToGet, $chunk, true);
		$results = collect([]);

		$pager->page(function($pager) use (&$results, $query, $force) {
			$params = [
				'type' => 'playlist',
				'maxResults' => $pager->getChunk(),
				'part' => 'id, snippet',
				'pageToken' => $pager->getToken(),
				'q' => $query
			];

			$cacheKey = $query . ':playlists:' . $pager->getPage();

			if($force === true) {
				Cache::forget($cacheKey);
			}

			$apiData = Cache::remember($cacheKey, env('API_CACHE_MINUTES', 720), function() use ($params) {
				return $this->source->search($params);
			});

			$results = $results->merge($apiData['items']);

			return $apiData;
		});

		return Playlist::createFromCollection($results);
	}

}