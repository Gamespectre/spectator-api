<?php

namespace Spectator\Lib\Repositories;

use Spectator\Game;

class YoutubeRepository {

	private $videoRepo;
	private $seriesRepo;
	private $creatorRepo;

	public function __construct(VideoRepository $videoRepo, SeriesRepository $seriesRepo, CreatorRepository $creatorRepo)
	{
		$this->videoRepo = $videoRepo;
		$this->seriesRepo = $seriesRepo;
		$this->creatorRepo = $creatorRepo;
	}

	public function saveAll(array $data, Game $assocGame)
	{
		$seriesModels = [];

		if(isset($data['series'])) {
			$seriesModels = $this->saveSeries($data['series'], $assocGame);
		}

		$videoModels = $this->saveVideos($data['videos'], $assocGame, $seriesModels);
		$this->saveCreators($data['creators'], $videoModels);
	}

	public function saveSeries(array $data, Game $game)
	{
		$modelsAndData = [];

		foreach($data as $series) {
			$model = $this->seriesRepo->createModel($series);
			$model->game()->associate($game);
			$model->save();

			$modelsAndData[] = [
				'model' => $model,
				'data' => $series
			];
		}

		return $modelsAndData;
	}

	public function saveVideos(array $data, Game $game, array $series = [])
	{
		$modelsAndData = [];
		$playlistIds = empty($series) ? [] : array_column(array_column($series, 'data'), 'playlist_id');

		foreach($data as $video) {
			$model = $this->videoRepo->createModel($video);

			if(!empty($series)) {
				$seriesModel = $series[array_search($video['playlist_id'], $playlistIds)]['model'];
				$model->series()->detach($seriesModel->id);
				$model->series()->attach($seriesModel->id);
			}

			$model->game()->associate($game);
			$model->save();

			$modelsAndData[] = [
				'model' => $model,
				'data' => $video
			];
		}

		return $modelsAndData;
	}

	public function saveCreators(array $data, array $videos)
	{
		$modelsAndData = [];
		$videoChannels = array_column(array_column($videos, 'data'), 'channel_id');

		foreach($data as $creator) {
			$model = $this->creatorRepo->createModel($creator);

			foreach(array_keys($videoChannels, $creator['channel_id']) as $videoKey) {
				$videoModel = $videos[$videoKey]['model'];
				$videoModel->creator()->associate($model->id);
				$videoModel->save();
			}

			$modelsAndData[] = [
				'model' => $model,
				'data' => $creator
			];
		}

		return $modelsAndData;
	}
}