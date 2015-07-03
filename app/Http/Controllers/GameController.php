<?php

namespace Spectator\Http\Controllers;

use Spectator\Repositories\GameRepository;
use Spectator\Transformers\GameTransformer;
use Spectator\Traits\FractalDataTrait;

use Illuminate\Http\Request;
use League\Fractal\Manager;

class GameController extends ApiController {

    use FractalDataTrait;

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
    }

    public function getIndex()
    {
        $model = $this->repo->getAll();
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