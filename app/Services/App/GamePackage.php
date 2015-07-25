<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;
use Spectator\Events\PackageSaved;
use Spectator\Repositories\GameRepository;

class GamePackage extends Package
{
    protected $handlers = [
        'game' => 'game'
    ];

    public function save()
    {
        $data = $this->getData();
        \App::make(GameRepository::class)->saveGames($data);

        event(new PackageSaved($this));
    }
}