<?php

namespace Spectator\Repositories;

use Illuminate\Support\Collection;
use Spectator\Services\App\Package;

class YoutubeRepository {

    /**
     * @var GameRepository
     */
    private $game;

    public function __construct(GameRepository $game)
	{
        $this->game = $game;
    }

	public function savePlaylists(Collection $data)
	{
		$data->each(function($item, $key) {
			$item->persist();
            $game = $this->game->get($item->relatedGame);

            if(!is_null($game)) {
                $item->relatesToGame($game);
            }
		});
	}

	public function saveVideos(Collection $data)
	{
		$data->each(function($item) {
			$item->persist();

            $game = $this->game->get($item->relatedGame);

            if(!is_null($game)) {
                $item->relatesToGame($game);
            }
		});
	}

	public function saveChannels(Collection $data)
	{
		$data->each(function($item, $key) {
			$item->persist();

            $game = $this->game->get($item->relatedGame);

            if(!is_null($game)) {
                $item->relatesToGame($game);
            }
		});
	}
}