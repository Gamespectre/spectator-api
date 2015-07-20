<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use Spectator\Events\Game\Search as GameSearch;
use Spectator\Events\SaveCachedPackage;
use Spectator\Events\SavePackage;
use Spectator\Events\Youtube\Search as YoutubeSearch;
use Spectator\Http\Requests;
use Spectator\Repositories\GameRepository;
use Spectator\Services\Youtube\YoutubeServiceManager;
use Spectator\Sources\GiantBombSource;

class AdminController extends Controller
{
    /**
     * @var GameRepository
     */
    private $game;

    public function __construct(GameRepository $game)
    {
        $this->game = $game;
    }

    /*
     *  Games actions
     */

    public function postAddGame(Request $request)
    {
        $channel = 'gameadd';
        $query = $request->input('query');
        $method = 'get';

        return $this->getGames($channel, $method, $query);
    }

    public function postSearchGame(Request $request)
    {
        $channel = 'gamesearch';
        $query = $request->input('query');
        $method = 'search';

        return $this->getGames($channel, $method, $query);
    }

    private function getGames($channel, $method, $query)
    {
        event(new GameSearch([
            'query' => $query,
            'method' => $method,
            'channel' => $channel
        ]));

        return \Response::json(['channel' => $channel]);
    }

    /*
     *  Content actions
     */

    public function postGameContent(Request $request)
    {
        $channel = 'contentadd';

        $game = $request->input('query');
        $gameModel = $this->game->get((int) $game);

        event(new YoutubeSearch([
            'game' => $gameModel,
            'channel' => $channel
        ]));

        return \Response::json(['channel' => $channel]);
    }

    /*
     *  Package actions
     */

    public function postGetPackageData(Request $request)
    {
        $packageId = $request->input('packageId');
        $packageData = \Cache::get($packageId);

        if(is_null($packageData)) {
            return \Response::json([
                "success" => false,
                "message" => "Your package timed out, or might have never existed."
            ]);
        }

        $package = unserialize($packageData);
        $data = $package->getServices()->map(function($service, $key) {
            return $service->getData();
        })->toArray();

        return \Response::json($data);
    }

    public function postSavePackage(Request $request)
    {
        $packageId = $request->input('packageId');
        $saveDatamodels = $request->input('saveData');
        $channel = 'packagesave';

        event(new SaveCachedPackage([
            'package' => $packageId,
            'channel' => $channel,
            'data' => $saveDatamodels
        ]));

        return \Response::json(['channel' => $channel]);
    }
}
