<?php

namespace Spectator\Services\Youtube;

use Spectator\Game;
use Spectator\Repositories\VideoRepository;
use Spectator\Repositories\SeriesRepository;
use Spectator\Repositories\CreatorRepository;
use Spectator\Repositories\YoutubeRepository;

set_time_limit(0);

class YoutubeServiceManager {

	public $videos;
	public $creators;
	public $playlists;
	public $youtubeRepo;

	public function __construct(
        YoutubeRepository $youtube,
        PlaylistService $playlists,
        VideoService $videos,
        ChannelService $creators
    )
	{
		$this->youtubeRepo = $youtube;
		$this->playlists = $playlists;
		$this->creators = $creators;
		$this->videos = $videos;
	}

    public function subscribe($events)
    {
        //
    }

	public function searchYoutubeContent($initalQuery, $playlistsToGet = 10, $force = false)
	{
		$playlists = $this->playlists->searchPlaylists($initalQuery, $playlistsToGet, $force);
		$videos = $this->videos->getVideosForPlaylists($playlists);
		$creators = $this->creators->getCreatorsForVideos($videos);

		$data = [
			'videos' => $videos,
			'series' => $playlists,
			'creators' => $creators
		];

		return $data;
	}

	public function addPlaylists(array $playlistIds, Game $assocGame)
	{
		$playlists = $this->playlists->getPlaylists(collect($playlistIds));
		$videos = $this->videos->getVideosForPlaylists($playlists);
		$creators = $this->creators->getCreatorsForVideos($videos);

		$data = [
			'videos' => $videos,
			'series' => $playlists,
			'creators' => $creators
		];

		$this->youtubeRepo->saveAll($data, $assocGame);

		return $data;
	}

	public function addVideo($videoId, Game $assocGame)
	{
		$video = $this->videos->getVideo($videoId);
		$creator = $this->creators->getCreator($video->channel);

		$data = [
			'videos' => collect([$video]),
			'creators' => collect([$creator])
		];

		$this->youtubeRepo->saveAll($data, $assocGame);

		return $data;
	}

}