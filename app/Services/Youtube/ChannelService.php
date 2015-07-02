<?php

namespace Spectator\Services\Youtube;

use Cache;
use Spectator\Traits\YoutubeDataTransformerTrait;
use Spectator\Sources\YoutubeSource;

set_time_limit(0);

class ChannelService {

	use YoutubeDataTransformerTrait;

	public $creators = [];
	private $source;

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

	public function getCreatorsForVideos(array $channelIds, $force = false)
	{
		$cumulativeResults = [];

		$uniqueIDs = array_unique($channelIds, SORT_REGULAR);

		foreach($uniqueIDs as $channelId)
		{
			$cumulativeResults = array_merge($cumulativeResults, $this->getCreator($channelId, $force));
		}

		$this->creators = $this->createData($cumulativeResults, 'createCreatorItem');

		return $this->creators;
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

		return $result['items'];
	}

}