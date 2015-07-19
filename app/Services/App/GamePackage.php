<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;
use Spectator\Repositories\GameRepository;

class GamePackage extends Package
{

    public function saveAll()
    {
        $data = $this->getData('game');
        \App::make(GameRepository::class)->saveGames($data);
    }

    public function saveOnly(Collection $data)
    {
        $games = $this->getData('game');

        $games->each(function($game) use ($data) {
            $save = $data->get($game->id);

            if($save) {
                $game->persist();
            }
        });
    }
}