<?php

namespace Spectator\Sources;

use Spectator\Interfaces\VideoSourceInterface;
use Spectator\Traits\YoutubeClientTrait;
use Google_Service_YouTube;

class YoutubeSource implements VideoSourceInterface {

	use YoutubeClientTrait;

	private $youtube;

	public function __construct()
	{
		$client = $this->createGoogleClient();
		$this->youtube = new Google_Service_YouTube($client);
	}

	public function search($params)
	{
		return $this->youtube->search->listSearch('id, snippet', $params);
	}

	public function getVideo($params)
	{
		return $this->youtube->videos->listVideos('id, snippet, statistics', $params);
	}

	public function getSeries($params)
	{
		return $this->youtube->playlists->listPlaylists('id, snippet', $params);
	}

	public function getCreator($params)
	{
		return $this->youtube->channels->listChannels('brandingSettings, snippet, id, statistics', $params);
	}

	public function getVideosInSeries($params)
	{
		return $this->youtube->playlistItems->listPlaylistItems('id, snippet', $params);
	}

}
