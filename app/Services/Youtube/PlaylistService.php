<?php

namespace Spectator\Services\Youtube;

use Cache;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Support\Collection;
use Spectator\Datamodels\Playlist;
use Spectator\Events\Youtube\PlaylistsRetrieved;
use Spectator\Interfaces\PackageHandler;
use Spectator\Repositories\SeriesRepository;
use Spectator\Series;
use Spectator\Services\ApiService;
use Spectator\Sources\YoutubeSource;
use Spectator\Traits\PackagesData;
use Spectator\Video;

set_time_limit(0);

class PlaylistService extends ApiService {

	private $source;

    public $actions = [
        'search' => 'searchPlaylists',
        'add' => 'getPlaylist'
    ];
    /**
     * @var SeriesRepository
     */
    private $repo;

    public function __construct(YoutubeSource $source, SeriesRepository $repo)
	{
		$this->source = $source;
        $this->repo = $repo;
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

		return Playlist::createData(collect($results['items']));
	}

	public function searchPlaylists($query, $force = false)
	{
		$params = [
			'type' => 'playlist',
			'maxResults' => 50,
			'part' => 'id, snippet',
			'q' => $query
		];

		$cacheKey = $query . ':playlists';

		if($force === true) {
			Cache::forget($cacheKey);
		}

		$results = Cache::remember($cacheKey, env('API_CACHE_MINUTES', 720), function() use ($params) {
			return $this->source->search($params);
		});

		return Playlist::createData(collect($results['items']));
	}

}