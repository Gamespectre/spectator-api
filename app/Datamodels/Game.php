<?php

namespace Spectator\Datamodels;

use Carbon\Carbon;
use Spectator\Game as GameModel;

class Game extends Datamodel
{
    public $uniqueKey = 'api_id';
    protected $modelClass = GameModel::class;

    public function transform($raw)
    {
        return [
            'title' => [$raw->getName()],
            'id' => [$raw->getId(), 'api_id'],
            'description' => [$raw->getDeck()],
            'franchise' => [$raw->getFranchises()[0]['name']],
            'year' => [Carbon::parse($raw->getOriginalReleaseDate())->year],
            'rating' => [$raw->getOriginalGameRating()[0]['name']],
            'imageUrl' => [$raw->getImage()['super_url'], 'image_url'],
        ];
    }
}