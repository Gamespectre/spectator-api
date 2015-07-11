<?php

namespace Spectator\Transformers;

use League\Fractal\TransformerAbstract;
use Spectator\Creator;

class CreatorTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'videos', 'games', 'series'
	];

	public function transform(Creator $creator) {

		return [
			'name' => $creator->name,
			'channel_id' => $creator->channel_id,
			'lang' => $creator->primary_language,
			'id' => $creator->id
		];
	}

	public function includeVideos(Creator $creator)
	{
		$videos = $creator->videos;
		return $this->collection($videos, new VideoTransformer);
	}

    public function includeSeries(Creator $creator)
    {
        $series = $creator->series;
        return $this->collection($series, new SeriesTransformer);
    }

	public function includeGames(Creator $creator)
	{
		$games = $creator->games;
		return $this->collection($games, new GameTransformer);
	}
}