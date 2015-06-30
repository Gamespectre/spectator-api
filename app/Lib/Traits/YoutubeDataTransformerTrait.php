<?php

namespace Spectator\Lib\Traits;

trait YoutubeDataTransformerTrait {

	protected function createData($data, $processor)
	{
		$processed = [];

		foreach($data as $item) {
			$processedItem = call_user_func([$this, $processor], $item);
			$processed[] = $processedItem;
		}

		return array_unique($processed, SORT_REGULAR);
	}

	protected function createPlaylistItem($raw)
	{
		return [
			'playlist_id' => $raw['id']['playlistId'],
			'channel_id' => $raw['snippet']['channelId'],
			'title' => $raw['snippet']['title'],
			'timestamp' => $raw['snippet']['publishedAt'],
		];
	}

	protected function createVideoItem($raw)
	{
		$data = $raw['snippet'];

		return [
			'video_id' => $data['resourceId']['videoId'],
			'channel_id' => $data['channelId'],
			'title' => $data['title'],
			'description' => $data['description'],
			'published_at' => $data['publishedAt'],
		];
	}

	protected function createCreatorItem($raw)
	{
		return [
			'channel_id' => $raw['id'],
			'name' => $raw['snippet']['title'],
			'subscribers' => $raw['statistics']['subscriberCount'],
			'description' => $raw['snippet']['description'],
			'birthday' => $raw['snippet']['publishedAt'],
			'avatar_url' => $raw['snippet']['thumbnails']['high']['url'],
			'image_url' => $raw['brandingSettings']['image']['bannerTabletExtraHdImageUrl'],
		];
	}

}