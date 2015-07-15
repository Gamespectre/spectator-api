<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use Spectator\Repositories\CreatorRepository;
use Spectator\Traits\FractalDataTrait;
use Spectator\Transformers\CreatorTransformer;
use Spectator\Transformers\GameTransformer;
use Spectator\Transformers\SeriesTransformer;
use Spectator\Transformers\VideoTransformer;

class CreatorController extends ApiController {

    use FractalDataTrait;

    protected $includesSet;
    protected $perPage = 20;
    private $repo;
    protected $fractal;
    protected $request;
    private $transformer;

    public function __construct(CreatorRepository $repo, Manager $fractal, CreatorTransformer $transformer, Request $request)
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
        $this->setIncludes('games,series');

        $creators = $this->repo->getAll($this->perPage);
        $data = $this->createPagedCollection($creators, $this->transformer);
        return $this->respond($data);
    }

    public function getGames(GameTransformer $transformer, $creatorId)
    {
        $this->setIncludes('creators,series');

        $model = $this->repo->getGamesByCreator($creatorId);
        $data = $this->createCollectionData($model, $transformer);
        return $this->respond($data);
    }

    public function getSeries(SeriesTransformer $transformer, $creatorId)
    {
        $this->setIncludes('game,videos');

        $model = $this->repo->getSeriesByCreator($creatorId, $this->perPage);
        $data = $this->createPagedCollection($model, $transformer);
        return $this->respond($data);
    }

    public function getVideos(VideoTransformer $transformer, $creatorId)
    {
        $this->setIncludes('game,series');

        $model = $this->repo->getVideosByCreator($creatorId, $this->perPage);
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