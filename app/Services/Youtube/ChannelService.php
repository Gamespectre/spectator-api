<?php

namespace Spectator\Services\Youtube;

use Cache;
use Spectator\Datamodels\Channel;
use Spectator\Services\ApiService;
use Spectator\Sources\YoutubeSource;
use Illuminate\Support\Collection;

set_time_limit(0);

class ChannelService extends ApiService {

	private $source;

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

	public function getCreators(Collection $channelIds, $force = false)
	{
		return $this->getFromIds($channelIds, 'getCreator', $force);
	}

	public function getCreatorsForVideos(Collection $videos, $force = false)
	{
		return $videos
			->map(function($video) {
				return $video->channel;
			})
			->unique()
			->map(function($item, $key) use ($force) {
				return $this->getCreator($item, $force);
			});
	}

	public function updateCreator($channelId)
	{
		return $this->getCreator($channelId, true);
	}

	public function getCreator($channelId, $force = false)
	{
		$params = [
			'id' => $channelId,
			'maxResults' => 1
		];

		$cacheKey = $channelId;

		if($force === true) {
			Cache::forget($cacheKey);
		}

		$result = Cache::rememberForever($cacheKey, function() use ($params) {
			return $this->source->getCreator($params);
		});

		return Channel::createFromItem($result['items']);
	}

}