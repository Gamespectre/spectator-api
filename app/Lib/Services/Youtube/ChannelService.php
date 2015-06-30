<?php

namespace Spectator\Lib\Services\Youtube;

use Cache;
use Spectator\Lib\Traits\YoutubeDataTransformerTrait;
use Spectator\Lib\Sources\YoutubeSource;

set_time_limit(0);

class ChannelService {

	use YoutubeDataTransformerTrait;

	public $creators = [];
	private $source;

	public function __construct(YoutubeSource $source)
	{
		$this->source = $source;
	}

	public function getVideosCreators($videos, $force = false)
	{
		$cumulativeResults = [];

		$uniqueIDs = array_unique(array_column($videos, 'channel_id'), SORT_REGULAR);

		foreach($uniqueIDs as $channelId) {

			$result = $this->getVideoCreator($channelId, $force);
			$cumulativeResults[] = $result;
		}

		$this->creators = $this->createData($cumulativeResults, 'createCreatorItem');

		return $this->creators;
	}

	public function getVideoCreator($channelId, $force = false)
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

		return $result['items'][0];
	}

}