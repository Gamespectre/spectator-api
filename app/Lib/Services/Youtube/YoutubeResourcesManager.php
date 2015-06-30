<?php

namespace Spectator\Lib\Services\Youtube;

set_time_limit(0);

class YoutubeResourcesManager {

	private $videos;
	private $playlists;
	private $creators;

	public function __construct(PlaylistService $playlists, VideoService $videos, ChannelService $creators)
	{
		$this->playlists = $playlists;
		$this->creators = $creators;
		$this->videos = $videos;
	}

	public function searchYoutubeContent($initalQuery)
	{
		$playlists = $this->playlists->searchPlaylists($initalQuery);
		$videos = $this->videos->getPlaylistsVideos($playlists);
		$creators = $this->creators->getVideosCreators($videos);

		$data = [
			'videos' => $videos,
			'playlists' => $playlists,
			'creators' => $creators,
		];

		return $data;
	}

}