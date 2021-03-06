<?php

namespace Spectator\Services\Game;

use Cache;
use Spectator\Datamodels\Game;
use Spectator\Repositories\GameRepository;
use Spectator\Services\ApiService;
use Spectator\Sources\GiantBombSource;

set_time_limit(0);

class GameService extends ApiService
{
    private $source;
    protected $event = GameRetrieved::class;

    public $actions = [
        'add' => 'getGameByID',
        'search' => 'searchGame'
    ];

    public function __construct(GiantBombSource $source)
    {
        $this->source = $source;
    }

    public function getGameByID($id, $force = false)
    {
        if($force === true)
        {
            Cache::forget($id);
        }

        $gameData = Cache::rememberForever($id, function() use ($id) {
            return $this->source->get($id);
        });

        return Game::createData(collect([$gameData]));
    }

    public function searchGame($query, $force = false)
    {
        if($force === true)
        {
            Cache::forget($query);
        }

        $gameData = Cache::rememberForever($query, function() use ($query) {
            return $this->source->search($query);
        });

        return Game::createData(collect($gameData));
    }
}