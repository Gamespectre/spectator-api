<?php

namespace Spectator\Datamodels;

use Spectator\Game as GameModel;

class Generic extends Datamodel
{
    public $uniqueKey = 'api_id';
    protected $modelClass = GameModel::class;

    public function transform($array)
    {
        return [
            'title' => [$array['title'], 'name'],
            'id' => [$array['id'], 0]
        ];
    }
}