<?php

namespace Spectator\Services\Game;

use Cache;
use Spectator\Datamodels\Game;
use Spectator\Events\Game\GameRetrieved;
use Spectator\Services\ApiService;
use Spectator\Sources\GiantBombSource;
use Spectator\Traits\PackagesData;

class GameService extends ApiService
{
    use PackagesData;

    private $source;
    protected $event = GameRetrieved::class;

    public $actions = [
        'get' => 'getGameByID'
    ];

    public function __construct(GiantBombSource $source)
    {
        $this->source = $source;
    }

    public function getGameByID($id, $force = false)
    {
        if($force === true) {
            Cache::forget($id);
        }

        $gameData = Cache::rememberForever($id, function() use ($id) {
            return $this->source->get($id);
        });

        return Game::createFromItem($gameData);
    }
}