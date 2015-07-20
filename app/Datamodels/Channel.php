<?php

namespace Spectator\Datamodels;

use Carbon\Carbon;
use Spectator\Creator;
use Spectator\Game;

class Channel extends Datamodel {

	public $uniqueKey = 'channel_id';
	protected $modelClass = Creator::class;

	public function transform($raw)
	{
		$imageUrl = !isset($raw->brandingSettings->image->bannerImageUrl) ? "no image" : $raw->brandingSettings->image->bannerImageUrl;

		return [
			'id' => [$raw->id, 'channel_id'],
			'name' => [$raw->snippet->title],
			'subscribers' => [$raw->statistics->subscriberCount],
			'description' => [$raw->snippet->description],
			'birthday' => [Carbon::parse($raw->snippet->publishedAt)],
			'avatarUrl' => [$raw->snippet->thumbnails->high->url, 'avatar_url'],
			'imageUrl' => [$imageUrl, 'image_url'],
		];
	}

	public function relatesToGame(Game $model)
	{
		$this->model->games()->detach($model->id);
		$this->model->games()->attach($model->id);
		return $this->model;
	}
}