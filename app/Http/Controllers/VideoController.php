<?php

namespace Spectator\Http\Controllers;

use Spectator\Repositories\VideoRepository;
use Spectator\Transformers\VideoTransformer;
use Spectator\Traits\FractalDataTrait;
use Spectator\Services\Youtube\YoutubeResourcesManager;
use Spectator\Repositories\GameRepository;

use Illuminate\Http\Request;
use League\Fractal\Manager;

class VideoController extends ApiController {

    use FractalDataTrait;

    private $repo;
    private $fractal;
    private $request;
    private $transformer;

    public function __construct(VideoRepository $repo, Manager $fractal, VideoTransformer $transformer, Request $request)
    {
        $this->repo = $repo;
        $this->fractal = $fractal;
        $this->request = $request;
        $this->transformer = $transformer;
    }

    public function getGameVideos($gameid)
    {
        $model = $this->repo->getVideosByGame($gameid);
        $data = $this->createCollectionData($model, $this->transformer);
        return $this->respond($data);
    }

    public function getShow($id)
    {
        $model = $this->repo->get($id);
        $data = $this->createItemData($model, $this->transformer);
        return $this->respond($data);
    }
}