<?php

namespace Spectator\Processing\Series\Pipeline;

use Illuminate\Support\Collection;
use League\Pipeline\StageInterface;
use Spectator\Services\Youtube\ChannelService;

class GetSeriesCreator implements StageInterface
{
    /**
     * @var ChannelService
     */
    private $channelService;

    public function __construct(ChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    /**
     * @param Collection $payload
     * @return Collection
     */
    public function process($payload)
    {
        $dataCollection = $payload;

        if($dataCollection->has('series')) {
            $creatorId = $dataCollection->get('series')->channel_id;
            $creator = $this->channelService->getCreator($creatorId);
            $dataCollection = $dataCollection->put('creator', $creator->first());
        }

        return $dataCollection;
    }
}