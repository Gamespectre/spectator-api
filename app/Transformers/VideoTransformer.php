<?php

namespace Spectator\Transformers;

use League\Fractal\TransformerAbstract;
use Spectator\Video;

class VideoTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'creator', 'series', 'game'
	];

	public function transform(Video $video) {

		return [
			'title' => $video->title,
			'description' => $video->description,
			'youtube_id' => $video->video_id,
			'order' => $video->order,
			'id' => $video->id
		];
	}

	public function includeCreator(Video $video)
	{
		$creator = $video->creator;
		return $this->item($creator, new CreatorTransformer);
	}

	public function includeSeries(Video $video)
	{
		$series = $video->series;
		return $this->collection($series, new SeriesTransformer);
	}

	public function includeGame(Video $video)
	{
		$game = $video->game;
		return $this->item($game, new GameTransformer);
	}
}