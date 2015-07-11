<?php

namespace Spectator\Transformers;

use League\Fractal\TransformerAbstract;
use Spectator\Series;

class SeriesTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'videos', 'game', 'creator'
	];

	public function transform(Series $series) {

		return [
			'name' => $series->name,
			'youtube_id' => $series->playlist_id,
			'id' => $series->id
		];
	}

	public function includeGame(Series $series)
	{
		$game = $series->game;
		return $this->item($game, new GameTransformer);
	}

	public function includeVideos(Series $series)
	{
		$video = $series->videos;
		return $this->collection($video, new VideoTransformer);
	}

	public function includeCreator(Series $series)
	{
		$creator = $series->creator;
		return $this->item($creator, new CreatorTransformer);
	}
}