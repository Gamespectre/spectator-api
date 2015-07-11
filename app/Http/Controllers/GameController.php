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

    private $perPage;
    private $repo;
    private $fractal;
    private $request;
    private $transformer;

    public function __construct(GameRepository $repo, Manager $fractal, GameTransformer $transformer, Request $request)
    {
        $this->repo = $repo;
        $this->fractal = $fractal;
        $this->request = $request;
        $this->transformer = $transformer;
        $this->perPage = 5;

        if($this->request->has('include')) {
            $this->fractal->parseIncludes($this->request->include);
        }

        if($this->request->has('perPage')) {
            $this->perPage = $this->request->perPage;
        }
    }

    public function getIndex()
    {
        $models = $this->repo->getAll($this->perPage);
        $data = $this->createPagedCollection($models, $this->transformer);
        return $this->respond($data);
    }

    public function getVideos(VideoTransformer $transformer, $gameId)
    {
        $models = $this->repo->getVideosByGame($gameId, $this->perPage);
        $data = $this->createPagedCollection($models, $transformer);
        return $this->respond($data);
    }

    public function getSeries(SeriesTransformer $transformer, $gameId)
    {
        $this->fractal->parseIncludes('videos.creator');

        $model = $this->repo->getSeriesByGame($gameId, $this->perPage);
        $data = $this->createPagedCollection($model, $transformer);
        return $this->respond($data);
    }

    public function getCreators(CreatorTransformer $transformer, $gameId)
    {
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