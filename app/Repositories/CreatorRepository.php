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
}