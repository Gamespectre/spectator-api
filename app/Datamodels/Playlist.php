<?php

namespace Spectator\Datamodels;

use Carbon\Carbon;
use Spectator\Game;
use Spectator\Series;

class Playlist extends Datamodel {

	public $uniqueKey = 'playlist_id';
	protected $modelClass = Series::class;

	public function __construct($data = [])
	{
		parent::__construct($data);
	}

	public function relatesToGame(Game $model)
	{
		$this->model->game()->associate($model);
		$this->model->save();
		return $this->model;
	}

	public function transform($raw)
	{
		return [
			'id' => [isset($raw->id->playlistId) ? $raw->id->playlistId : $raw->id, 'playlist_id'],
			'channel' => [$raw->snippet->channelId, false],
			'name' => [$raw->snippet->title, 'name'],
			'publishedAt' => [Carbon::parse($raw->snippet->publishedAt), 'published_at'],
		];
	}
}