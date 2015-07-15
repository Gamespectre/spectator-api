<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use Spectator\Repositories\CreatorRepository;
use Spectator\Repositories\GameRepository;
use Spectator\Traits\FractalDataTrait;
use Spectator\Transformers\CreatorTransformer;
use Spectator\Transformers\GameTransformer;
use Spectator\Transformers\SeriesTransformer;
use Spectator\Transformers\VideoTransformer;

class GameController extends ApiController {

    use FractalDataTrait;

    protected $includesSet = false;
    private $perPage = 10;
    private $repo;
    protected $fractal;
    protected $request;
    private $transformer;

    public function __construct(GameRepository $repo, Manager $fractal, GameTransformer $transformer, Request $request)
    {
        $this->repo = $repo;
        $this->fractal = $fractal;
        $this->request = $request;
        $this->transformer = $transformer;

        if($this->request->has('include')) {
            $this->setIncludes($this->request->include);
            $this->includesSet = true;
        }

        if($this->request->has('perPage')) {
            $this->perPage = $this->request->perPage;
        }
    }

    public function getIndex()
    {
        $this->setIncludes('creators,series');

        $models = $this->repo->getAll($this->perPage);
        $data = $this->createPagedCollection($models, $this->transformer);
        return $this->respond($data);
    }

    public function getVideos(VideoTransformer $transformer, $gameId)
    {
        $this->setIncludes('creators,series');

        $models = $this->repo->getVideosByGame($gameId, $this->perPage);
        $data = $this->createPagedCollection($models, $transformer);
        return $this->respond($data);
    }

    public function getSeries(SeriesTransformer $transformer, $gameId)
    {
        $this->setIncludes('creators');

        $model = $this->repo->getSeriesByGame($gameId, $this->perPage);
        $data = $this->createPagedCollection($model, $transformer);
        return $this->respond($data);
    }

    public function getCreators(CreatorTransformer $transformer, $gameId)
    {
        $this->setIncludes('series, games');

        $model = $this->repo->getCreatorsByGame($gameId, $this->perPage);
        $data = $this->createPagedCollection($model, $transformer);
        return $this->respond($data);
    }

    public function getShow($id)
    {
        $model = $this->repo->get($id);
        $data = $this->createItemData($model, $this->transformer);
        return $this->respond($data);
    }
}