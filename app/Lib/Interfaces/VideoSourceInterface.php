<?php

namespace Spectator\Lib\Interfaces;

interface VideoSourceInterface {
    public function search($params);
    public function getVideo($params);
    public function getSeries($params);
    public function getCreator($params);
    public function getVideosInSeries($params);
}
