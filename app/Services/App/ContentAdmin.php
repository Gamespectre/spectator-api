<?php

namespace Spectator\Services\App;

class ContentAdmin
{
    public function __construct()
    {

    }

    public function addGame()
    {

    }

    public function searchGame($query, $method)
    {
        $channel =
        event(new GameSearch([
            'query' => $query,
            'method' => $method,
            'channel' => $channel
        ]));
    }

    public function addPlaylist()
    {

    }

    public function searchPlaylist()
    {

    }

    public function addVideo()
    {

    }

    public function searchVideo()
    {

    }
}