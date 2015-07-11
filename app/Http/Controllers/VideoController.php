<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use Spectator\Repositories\VideoRepository;
use Spectator\Traits\FractalDataTrait;
use Spectator\Transformers\CreatorTransformer;
use Spectator\Transformers\GameTransformer;
use Spectator\Transformers\SeriesTransformer;
use Spectator\Transformers\VideoTransformer;

class VideoController extends ApiController {

    use FractalDataTrait;

    private $repo;
    protected $fractal;
    protected $request;
    protected $includesSet = false;
    private $transformer;
    private $perPage;

    public function __construct(VideoRepository $repo, Manager $fractal, VideoTransformer $transformer, Request $request)
    {
        $this->repo = $repo;
        $this->fractal = $fractal;
        $this->request = $request;
        $this->transformer = $transformer;
        $this->perPage = 10;

        if($this->request->has('include')) {
            $this->setIncludes($this->request->include);
            $this->includesSet = true;
        }

        if($this->request->has('perPage')) {
            $this->perPage = $this->request->perPage;
        }
    }

    public function getSeries(SeriesTransformer $transformer, $videoId)
    {
        $this->setIncludes('creator,game,videos');

        $model = $this->repo->getSeriesByVideo($videoId, $this->perPage);
        $data = $this->createPagedCollection($model, $transformer);
        return $this->respond($data);
    }

    public function getGame(GameTransformer $transformer, $videoId)
    {
        $this->setIncludes('creators,series');

        $model = $this->repo->getGameByVideo($videoId);
        $data = $this->createItemData($model, $transformer);
        return $this->respond($data);
    }

    public function getCreator(CreatorTransformer $transformer, $videoId)
    {
        $this->setIncludes('series,games');

        $model = $this->repo->getCreatorByVideo($videoId);
        $data = $this->createItemData($model, $transformer);
        return $this->respond($data);
    }

    public function getShow($id)
    {
        $this->setIncludes('creator,game,series');

        $model = $this->repo->get($id);
        $data = $this->createItemData($model, $this->transformer);
        return $this->respond($data);
    }
}