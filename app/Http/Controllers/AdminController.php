<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use Response;
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

        return response()->json([
            'success' => true,
            'channel' => $channel
        ]);
    }

    public function postSearchGame(Request $request)
    {
        $query = $request->input('query');
        $channel = $this->admin->searchGame($query);

        return response()->json([
            'success' => true,
            'channel' => $channel
        ]);
    }

    /*
     *  Content actions
     */

    public function postSearchContent(Request $request)
    {
        $query = $request->input('query');
        $resource = $request->input('resource');

        $channel = $this->admin->searchContent($query, $resource);

        return response()->json([
            'success' => true,
            'channel' => $channel
        ]);
    }


    public function postAddContent(Request $request)
    {
        $resourceId = $request->input('query');
        $resource = $request->input('resource');

        $channel = $this->admin->addContent($resourceId, $resource);

        return response()->json([
            'success' => true,
            'channel' => $channel
        ]);
    }

    /*
     *  Package actions
     */

    public function postGetPackageData(Request $request)
    {
        $packageId = $request->input('packageId');

        $data = $this->admin->getPackageData($packageId);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function postSavePackage(Request $request)
    {
        $packageId = $request->input('packageId');
        $saveDatamodels = $request->input('saveData');

        $channel = $this->admin->savePackage($packageId, $saveDatamodels);

        return response()->json([
            'success' => true,
            'channel' => $channel
        ]);
    }
}
