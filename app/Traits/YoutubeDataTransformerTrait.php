<?php

namespace Spectator\Traits;

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
		return [
			'playlist_id' => isset($raw->id->playlistId) ? $raw->id->playlistId : $raw->id,
			'channel_id' => $raw->snippet->channelId,
			'name' => $raw->snippet->title,
			'published_at' => $raw->snippet->publishedAt,
		];
	}

	public function createPlaylistVideoItem($raw)
	{
		$data = $raw->snippet;

		return [
			'video_id' => $data->resourceId->videoId,
			'channel_id' => $data->channelId,
			'playlist_id' => $data->playlistId,
			'title' => $data->title,
			'description' => $data->description,
			'published_at' => $data->publishedAt,
		];
	}

	public function createVideoItem($raw)
	{
		return [
			'video_id' => $raw->id,
			'playlist_id' => isset($raw->snippet->playlistId) ? $raw->snippet->playlistId : "",
			'channel_id' => $raw->snippet->channelId,
			'title' => $raw->snippet->title,
			'description' => $raw->snippet->description,
			'published_at' => $raw->snippet->publishedAt,
			'image_url' => $raw->snippet->thumbnails->high->url,
		];
	}

	public function createCreatorItem($raw)
	{
		return [
			'channel_id' => $raw->id,
			'name' => $raw->snippet->title,
			'subscribers' => $raw->statistics->subscriberCount,
			'description' => $raw->snippet->description,
			'birthday' => $raw->snippet->publishedAt,
			'avatar_url' => $raw->snippet->thumbnails->high->url,
			'image_url' => !isset($raw->brandingSettings->image->bannerImageUrl) ? "no image" : $raw->brandingSettings->image->bannerImageUrl,
		];
	}

}