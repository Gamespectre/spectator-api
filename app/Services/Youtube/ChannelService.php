<?php

namespace Spectator\Services\Youtube;

use Cache;
use Illuminate\Support\Collection;
use Spectator\Datamodels\Channel;
use Spectator\Services\ApiService;
use Spectator\Sources\YoutubeSource;

set_time_limit(0);

class ChannelService extends ApiService {

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
		$creators = collect([]);

		$videos
            ->map(function($video) {
                return $video->channel;
            })
            ->unique()
            ->each(function($item) use (&$creators, $force) {
                $creators = $this->getCreator($item, $force)->merge($creators->all());
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

		return Channel::createData(collect($result['items']));
	}

}