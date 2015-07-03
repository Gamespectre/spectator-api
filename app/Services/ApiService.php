<?php

namespace Spectator\Services;

use Illuminate\Support\Collection;

abstract class ApiService {

	public function getFromIds(Collection $idCollection, $method, $force = false)
	{
		if($idCollection->isEmpty()) {
			return $idCollection;
		}

		return $idCollection
			->unique()
			->map(function($item, $key) use ($force) {
				return call_user_func([$this, $method], $item, $force);
			});
	}
}