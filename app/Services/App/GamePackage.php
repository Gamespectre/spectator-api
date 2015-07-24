<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;
use Spectator\Repositories\GameRepository;

class GamePackage extends Package
{

    public function save()
    {
        $data = $this->getData();
        \App::make(GameRepository::class)->saveGames($data);
    }
}