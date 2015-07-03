<?php

namespace Spectator\Repositories;

use Spectator\Game;
use Illuminate\Support\Collection;

class YoutubeRepository {

	public function __construct()
	{
	    //
	}

	public function saveAll(array $data, Game $assocGame)
	{
		$seriesModels = collect([]);

		if(isset($data['series'])) {
			$this->saveSeries($data['series'], $assocGame);
			$seriesModels = $data['series'];
		}

		$this->saveVideos($data['videos'], $assocGame, $seriesModels);
		$this->saveCreators($data['creators'], $data['videos']);
	}

	public function saveSeries(Collection $data, Game $game)
	{
		$data->each(function($item, $key) use ($game) {

			$item->persist();
			$item->relatesToGame($game);
		});
	}

	public function saveVideos(Collection $data, Game $game, Collection $series = null)
	{
		$data->each(function($item, $key) use ($series, $game) {
			$item->persist();
			$item->relatesToGame($game);

			if(!is_null($series) && !$series->isEmpty()) {

				$seriesIndex = $series->search(function($playlist, $key) use ($item) {
                    return $item->playlist === $playlist->id;
                });

                $seriesModel = $series->get($seriesIndex);

				$item->relatesToSeries($seriesModel->model);
			}
		});
	}

	public function saveCreators(Collection $data, Collection $videos)
	{
		$data->each(function($item, $key) use ($videos) {
			$item->persist();

			$videos->filter(function($video) use ($item) {
				return $item->id === $video->channel;
			})
            ->each(function($video) use ($item) {
                $video->relatesToCreator($item->model);
            });
		});
	}
}