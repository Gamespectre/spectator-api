<?php

namespace Spectator\Processing\Series\Pipeline;

use Illuminate\Support\Collection;
use League\Pipeline\StageInterface;
use Spectator\Services\Youtube\VideoService;

class GetSeriesVideos implements StageInterface
{
    /**
     * @var VideoService
     */
    private $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    /**
     * Process the payload.
     * @param Collection $payload
     * @return Collection
     */
    public function process($payload)
    {
        $dataCollection = $payload;

        if($dataCollection->has('series')) {
            $seriesId = $dataCollection->get('series')->playlist_id;
            $videos = $this->videoService->getVideosInPlaylist($seriesId);
            $dataCollection = $dataCollection->put('videos', $videos);
        }

        return $dataCollection;
    }
}