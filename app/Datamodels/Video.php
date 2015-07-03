<?php

namespace Spectator\Datamodels;

use Spectator\Creator;
use Spectator\Series;
use Spectator\Video as VideoModel;
use Spectator\Game;
use Carbon\Carbon;

class Video extends Datamodel {

	public $uniqueKey = 'video_id';
	protected $modelClass = VideoModel::class;

	public function __construct($data = [])
	{
		parent::__construct($data);
	}

	public function transform($raw)
	{
		return [
			'id' => [$raw->id, 'video_id'],
			'playlist' => [isset($raw->snippet->playlistId) ? $raw->snippet->playlistId : "", false],
			'channel' => [$raw->snippet->channelId, false],
			'title' => [$raw->snippet->title],
			'description' => [$raw->snippet->description],
			'publishedAt' => [Carbon::parse($raw->snippet->publishedAt), 'published_at'],
			'imageUrl' => [$raw->snippet->thumbnails->high->url, 'image_url'],
		];
	}

	public function relatesToGame(Game $model)
	{
		$this->model->game()->associate($model);
		$this->model->save();
		return $this->model;
	}

	public function relatesToSeries(Series $model)
	{
		$this->model->series()->detach($model->id);
		$this->model->series()->attach($model->id);
		return $this->model;
	}

	public function relatesToCreator(Creator $model)
	{
		$this->model->creator()->associate($model);
		$this->model->save();
		return $this->model;
	}
}