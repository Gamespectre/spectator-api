<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Response as IlluminateResponse;

class ApiController extends Controller
{

    protected $statusCode = IlluminateResponse::HTTP_OK;

    public function __construct()
    {

    }

    protected function setIncludes($includes)
    {
        if($this->includesSet)
        {
            return false;
        }

        $this->fractal->parseIncludes($includes);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}