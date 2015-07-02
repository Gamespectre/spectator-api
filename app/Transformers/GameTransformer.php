<?php

namespace Spectator\Transformers;

use League\Fractal\TransformerAbstract;
use Spectator\Game;

class GameTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'series', 'videos'
	];

	public function transform(Game $game) {

		return [
			'title' => $game->title,
			'description' => $game->description,
			'year' => $game->year,
			'rating' => $game->rating,
			'image' => $game->image_url,
			'franchise' => $game->franchise,
			'id' => $game->id
		];
	}

	public function includeVideos(Game $game)
	{
		$video = $game->videos;
		return $this->collection($video, new VideoTransformer);
	}

	public function includeSeries(Game $game)
	{
		$series = $game->series;
		return $this->collection($series, new SeriesTransformer);
	}
}