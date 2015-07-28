<?php

namespace Spectator\Datamodels;

use Carbon\Carbon;
use Spectator\Creator;
use Spectator\Game;
use Spectator\Series;
use Spectator\Video as VideoModel;

class PlaylistItem extends Datamodel {

	public $uniqueKey = 'video_id';
	protected $modelClass = VideoModel::class;
    public $relatedGame = null;

	public function __construct($data = [])
	{
		parent::__construct($data);
	}

	public function transform($raw)
	{
        $image = !is_null($raw->snippet->thumbnails) ? $raw->snippet->thumbnails->high->url : "No image";
        $order = isset($raw->order) ? $raw->order : 0;
        $id = $raw->snippet->resourceId->videoId;

		return [
			'id' => [$id, 'video_id'],
			'playlist' => [isset($raw->snippet->playlistId) ? $raw->snippet->playlistId : "", false],
			'channel' => [$raw->snippet->channelId, 'channel_id'],
			'title' => [$raw->snippet->title],
			'description' => [$raw->snippet->description],
			'order' => [$order],
			'publishedAt' => [Carbon::parse($raw->snippet->publishedAt), 'published_at'],
			'imageUrl' => [$image, 'image_url'],
		];
	}

	public function update($props)
	{
        if(isset($props['game'])) {
            $this->relatedGame = $props['game'];
        }

        return $this;
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