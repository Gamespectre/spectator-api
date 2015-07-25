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
        $subscribers = isset($raw->statistics->subscriberCount) ? $raw->statistics->subscriberCount : 0;

        $imageUrl = isset($raw->brandingSettings->image->bannerImageUrl) ? $raw->brandingSettings->image->bannerImageUrl :
            isset($raw->snippet->thumbnails->high->url) ? $raw->snippet->thumbnails->high->url : "No image";

        $id = isset($raw->id) && is_string($raw->id) ? $raw->id : $raw->id->channelId;

		return [
			'id' => [$id, 'channel_id'],
			'name' => [$raw->snippet->title],
			'subscribers' => [$subscribers],
			'description' => [$raw->snippet->description],
			'birthday' => [Carbon::parse($raw->snippet->publishedAt)],
			'avatarUrl' => [$raw->snippet->thumbnails->high->url, 'avatar_url'],
			'imageUrl' => [$imageUrl, 'image_url'],
		];
	}

	public function update($props)
	{
        return $this;
	}

	public function relatesToGame(Game $model)
	{
		$this->model->games()->detach($model->id);
		$this->model->games()->attach($model->id);
		return $this->model;
	}
}