<?php

namespace Spectator\Lib\Services\Youtube;

use Spectator\Game;
use Spectator\Lib\Repositories\VideoRepository;
use Spectator\Lib\Repositories\SeriesRepository;
use Spectator\Lib\Repositories\CreatorRepository;
use Spectator\Lib\Repositories\YoutubeRepository;

set_time_limit(0);

class YoutubeResourcesManager {

	public $videos;
	public $creators;
	public $playlists;
	public $youtubeRepo;

	public function __construct(YoutubeRepository $youtube, PlaylistService $playlists, VideoService $videos, ChannelService $creators)
	{
		$this->youtubeRepo = $youtube;
		$this->playlists = $playlists;
		$this->creators = $creators;
		$this->videos = $videos;
	}

	public function searchYoutubeContent($initalQuery, $playlistsToGet = 10)
	{
		$playlists = $this->playlists->searchPlaylists($initalQuery, $playlistsToGet);
		$videos = $this->videos->getVideosForPlaylists(array_column($playlists, 'playlist_id'));
		$creators = $this->creators->getCreatorsForVideos(array_column($videos, 'channel_id'));

		$data = [
			'videos' => $videos,
			'series' => $playlists,
			'creators' => $creators
		];

		return $data;
	}

	public function addPlaylists(array $playlistIds, Game $assocGame)
	{
		$playlists = $this->playlists->getPlaylists($playlistIds);
		$videos = $this->videos->getVideosForPlaylists(array_column($playlists, 'playlist_id'));
		$creators = $this->creators->getCreatorsForVideos(array_column($videos, 'channel_id'));

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
		$videoData = $this->videos->createVideoItem($video[0]);

		$creator = $this->creators->getCreator($videoData['channel_id']);
		$creatorData = $this->creators->createCreatorItem($creator[0]);

		$data = [
			'videos' => [$videoData],
			'creators' => [$creatorData]
		];

		$this->youtubeRepo->saveAll($data, $assocGame);

		return $data;
	}

}