<?php

namespace Spectator\Datamodels;

use Carbon\Carbon;
use Spectator\Game as GameModel;

class Game extends Datamodel
{
    public $uniqueKey = 'api_id';
    protected $modelClass = GameModel::class;

    public function transform($array)
    {
        $safe = $this->safe($array);

        return [
            'title' => [$safe('name', 'Unnamed')],
            'id' => [$safe('id', 'No id'), 'api_id'],
            'description' => [$safe('deck', 'No description')],
            'franchise' => [$safe(['franchises', 0, 'name'], 'Franchise not found')],
            'year' => [Carbon::parse($safe('original_release_date', 'now'))->year],
            'rating' => [$safe(['original_game_rating', 0, 'name'], 'Rating not found')],
            'imageUrl' => [$safe(['image', 'super_url'], 'no image'), 'image_url'],
        ];
    }

    private function safe($array)
    {
        return function($key, $fallback) use ($array)
        {
            if(!is_array($key))
            {
                return isset($array[$key]) ? $array[$key] : $fallback;
            }

            if(!isset($array[$key[0]]))
            {
                return $fallback;
            }

            $path = $array;

            foreach($key as $keyVal)
            {
                if(!isset($path[$keyVal])) {
                    $path = $fallback;
                    break;
                }

                $path = $path[$keyVal];
            }

            return $path;
        };
    }
}