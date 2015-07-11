<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use Spectator\Traits\FractalDataTrait;
use Spectator\Transformers\SearchTransformer;

class SearchController extends ApiController {

    use FractalDataTrait;

    private $fractal;
    private $request;
    private $transformer;

    public function __construct(Manager $fractal, SearchTransformer $transformer, Request $request)
    {
        $this->fractal = $fractal;
        $this->request = $request;
        $this->transformer = $transformer;

        if($this->request->has('include')) {
            $this->fractal->parseIncludes($this->request->include);
        }
    }
}