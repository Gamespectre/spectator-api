<?php

namespace Spectator\Http\Controllers;

use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Database\Eloquent\Collection;

class ApiController extends Controller
{

    protected $statusCode = IlluminateResponse::HTTP_OK;

    public function __construct()
    {

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