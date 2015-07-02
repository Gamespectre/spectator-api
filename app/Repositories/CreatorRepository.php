<?php

namespace Spectator\Repositories;

use Spectator\Video;
use Spectator\Creator;
use Spectator\Interfaces\RepositoryInterface;
use Carbon\Carbon;

class CreatorRepository implements RepositoryInterface {

	public function __construct() {

	}

	public function getAll()
	{
		return Creator::with('videos')->get();
	}

	public function get($id)
	{
		$creator = Creator::findOrFail($id)->load('videos')->get();
		return $creator->first();
	}

	public function createModel($data)
	{
		$model = Creator::where('channel_id', $data['channel_id'])->first();

		if(!is_null($model)) {
			return $model;
		}

		$props = [
			'name' => $data['name'],
			'channel_id' => $data['channel_id'],
			'subscribers' => $data['subscribers'],
			'description' => $data['description'],
			'image_url' => $data['image_url'],
			'avatar_url' => $data['avatar_url'],
			'birthday' => Carbon::parse($data['birthday']),
		];

		$model = Creator::create($props);
		return $model;
	}
}