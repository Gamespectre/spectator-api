<?php

namespace Spectator\Services\Youtube;

use Cache;
use Illuminate\Support\Collection;
use Spectator\Datamodels\Channel;
use Spectator\Events\Youtube\ChannelsRetrieved;
use Spectator\Interfaces\PackageHandler;
use Spectator\Services\ApiService;
use Spectator\Sources\YoutubeSource;
use Spectator\Traits\PackagesData;

set_time_limit(0);

class ChannelService extends ApiService implements PackageHandler {

    use PackagesData;

	private $source;
    protected $event = ChannelsRetrieved::class;

	public $actions = [
		'videos' => 'getCreatorsForVideos'
	];

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
		$creators = $videos
			->map(function($video) {
				return $video->channel;
			})
			->unique()
			->map(function($item, $key) use ($force) {
				return $this->getCreator($item, $force);
			});

		return $creators;
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