<?php

namespace Spectator\Services\App;

use Spectator\Repositories\GameRepository;

class GamePackage extends Package
{

    public function saveAll()
    {
        $data = $this->getData('game');
        \App::make(GameRepository::class)->saveGames($data);
    }
}