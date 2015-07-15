<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use Spectator\Repositories\SeriesRepository;
use Spectator\Traits\FractalDataTrait;
use Spectator\Transformers\CreatorTransformer;
use Spectator\Transformers\GameTransformer;
use Spectator\Transformers\SeriesTransformer;
use Spectator\Transformers\VideoTransformer;

class SeriesController extends ApiController {

    use FractalDataTrait;

    protected $includesSet;
    protected $perPage = 20;
    private $repo;
    protected $fractal;
    protected $request;
    private $transformer;

    public function __construct(SeriesRepository $repo, Manager $fractal, SeriesTransformer $transformer, Request $request)
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
        $series = $this->repo->getAll($this->perPage);
        $data = $this->createPagedCollection($series, $this->transformer);
        return $this->respond($data);
    }

    public function getVideos(VideoTransformer $transformer, $seriesId)
    {
        $this->setIncludes('creator,game');

        $models = $this->repo->getVideosInSeries($seriesId, $this->perPage);
        $data = $this->createPagedCollection($models, $transformer);
        return $this->respond($data);
    }

    public function getGame(GameTransformer $transformer, $seriesId)
    {
        $this->setIncludes('creators,series');

        $model = $this->repo->getGameOfSeries($seriesId);
        $data = $this->createItemData($model, $transformer);
        return $this->respond($data);
    }

    public function getCreator(CreatorTransformer $transformer, $seriesId)
    {
        $this->setIncludes('series,games,videos');

        $model = $this->repo->getCreatorOfSeries($seriesId);
        $data = $this->createItemData($model, $transformer);
        return $this->respond($data);
    }

    public function getShow($id)
    {
        $model = $this->repo->get($id);
        $data = $this->createItemData($model, $this->transformer);
        return $this->respond($data);
    }
}