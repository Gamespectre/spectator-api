<?php

namespace Spectator\Traits;

use Spectator\Datamodels\Playlist;
use Spectator\Datamodels\Channel;
use Spectator\Datamodels\Video;

trait YoutubeDataTransformerTrait {

	public function createData($data, $processor)
	{
		$processed = [];

		foreach($data as $item) {
			$processedItem = call_user_func([$this, $processor], $item);
			$processed[] = $processedItem;
		}

		return array_unique($processed, SORT_REGULAR);
	}

	public function createPlaylistItem($raw)
	{
		$props = [
			'id' => isset($raw->id->playlistId) ? $raw->id->playlistId : $raw->id,
			'channel' => $raw->snippet->channelId,
			'name' => $raw->snippet->title,
			'publishedAt' => $raw->snippet->publishedAt,
		];

		return new Playlist($props);
	}

	public function createVideoItem($raw)
	{
		$props = [
			'id' => $raw->id,
			'playlist' => isset($raw->snippet->playlistId) ? $raw->snippet->playlistId : "",
			'channel' => $raw->snippet->channelId,
			'title' => $raw->snippet->title,
			'description' => $raw->snippet->description,
			'publishedAt' => $raw->snippet->publishedAt,
			'imageUrl' => $raw->snippet->thumbnails->high->url,
		];

		return new Video($props);
	}

	public function createCreatorItem($raw)
	{
		$props = [
			'id' => $raw->id,
			'name' => $raw->snippet->title,
			'subscribers' => $raw->statistics->subscriberCount,
			'description' => $raw->snippet->description,
			'birthday' => $raw->snippet->publishedAt,
			'avatarUrl' => $raw->snippet->thumbnails->high->url,
			'imageUrl' => !isset($raw->brandingSettings->image->bannerImageUrl) ? "no image" : $raw->brandingSettings->image->bannerImageUrl,
		];

		return new Channel($props);
	}

}