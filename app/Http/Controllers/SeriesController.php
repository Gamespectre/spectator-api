<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use Spectator\Repositories\SeriesRepository;
use Spectator\Traits\FractalDataTrait;
use Spectator\Transformers\SeriesTransformer;

class SeriesController extends ApiController {

    use FractalDataTrait;

    private $repo;
    private $fractal;
    private $request;
    private $transformer;

    public function __construct(SeriesRepository $repo, Manager $fractal, SeriesTransformer $transformer, Request $request)
    {
        $this->repo = $repo;
        $this->fractal = $fractal;
        $this->request = $request;
        $this->transformer = $transformer;

        if($this->request->has('include')) {
            $this->fractal->parseIncludes($this->request->include);
        }
    }

    public function getGameSeries($gameid)
    {
        $model = $this->repo->getSeriessByGame($gameid);
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