<?php

namespace Spectator\Repositories;

use Illuminate\Support\Collection;
use Spectator\Game;
use Spectator\Services\App\Package;

class YoutubeRepository {

	public function __construct()
	{
	    //
	}

	public function saveAll(Collection $data, Game $assocGame)
	{
		if($data->has('playlist')) {
			$this->saveSeries($data->get('playlist'), $assocGame);
		}

		$this->saveVideos($data->get('video'), $assocGame, $data->get('playlist'));
		$this->saveCreators($data->get('channel'), $data->get('video'));
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