<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use Spectator\Events\Game\Search as GameSearch;
use Spectator\Events\SaveCachedPackage;
use Spectator\Events\SavePackage;
use Spectator\Events\Youtube\Search as YoutubeSearch;
use Spectator\Http\Requests;
use Spectator\Repositories\GameRepository;
use Spectator\Services\App\ContentAdmin;
use Spectator\Services\Youtube\YoutubeServiceManager;
use Spectator\Sources\GiantBombSource;

class AdminController extends Controller
{
    /**
     * @var GameRepository
     */
    private $game;
    /**
     * @var ContentAdmin
     */
    private $admin;

    public function __construct(GameRepository $game, ContentAdmin $admin)
    {
        $this->game = $game;
        $this->admin = $admin;
    }

    /*
     *  Games actions
     */

    public function postAddGame(Request $request)
    {
        $query = $request->input('query');

        $channel = $this->admin->addGame($query);

        return \Response::json(['channel' => $channel]);
    }

    public function postSearchGame(Request $request)
    {
        $query = $request->input('query');

        $channel = $this->admin->searchGame($query);

        return \Response::json(['channel' => $channel]);
    }

    /*
     *  Content actions
     */

    public function postSearchPlaylists(Request $request)
    {
        $game = $request->input('query');
        $gameModel = $this->game->get((int) $game);

        $channel = $this->admin->searchPlaylist($gameModel);

        return \Response::json(['channel' => $channel]);
    }

    private function getYoutubeContent()
    {
        event(new YoutubeSearch([
            'game' => $gameModel,
            'channel' => $channel
        ]));


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
