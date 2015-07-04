<?php

namespace Spectator\Services\App;

class YoutubePackage extends Package
{
    protected $requiredParams = ['game'];

    public function __construct($data)
    {
        parent::__construct($data);
    }
}