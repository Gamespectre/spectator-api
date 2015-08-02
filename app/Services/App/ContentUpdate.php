<?php

namespace Spectator\Services\App;

use Spectator\Series;
use Spectator\Services\Youtube\ChannelService;
use Spectator\Services\Youtube\VideoService;

class ContentUpdate
{
    /**
     * @var VideoService
     */
    private $videoService;
    /**
     * @var ChannelService
     */
    private $channelService;
    /**
     * @var Series
     */
    private $series;

    public function __construct(Series $series, VideoService $videoService, ChannelService $channelService)
    {
        $this->videoService = $videoService;
        $this->channelService = $channelService;
        $this->series = $series;
    }

    public function update()
    {
        $playlists = Series::orderBy('updated_at', 'asc')->take(5)->get();
        $this->getContent($playlists);

        print "Content updated!";
    }

    public function populate()
    {
        $playlists =
        $this->getContent($playlists);

        print "Content populated! Playlists: " . $playlists->count();
    }

    private function getContent($playlists)
    {
        $creators = $this->getCreators($playlists);
        $videos = $this->getPlaylistVideos($playlists);

        $this->saveAndAttach($playlists, $creators, $videos);
    }

    private function saveAndAttach($playlists, $creators, $videos)
    {
        $playlists->each(function($playlist) use ($creators, $videos) {
            $game = $playlist->game()->first();

            $creatorIndex = $creators->search(function($item) use ($playlist) {
                return $item->id === $playlist->channel_id;
            });

            $creator = $creators[$creatorIndex];
            $creator->persist();

            $creator->relatesToGame($game);
            $playlist->creator()->associate($creator->model);

            $videos->where('series', $playlist->id)->first()['videos']
                ->each(function($video) use ($playlist, $creator, $game) {
                    $video->persist();
                    $video->relatesToGame($game);
                    $video->relatesToSeries($playlist);
                    $video->relatesToCreator($creator->model);
                });

            $playlist->touch();
            $playlist->save();
        });
    }

    private function getPlaylistVideos($playlists)
    {
        return $playlists->map(function($item) {
            return [
                'series' => $item->id,
                'videos' => $this->videoService->getVideosInPlaylist($item->playlist_id)
            ];
        });
    }

    private function getCreators($playlists)
    {
        $ids = $playlists->map(function($item) {
            return $item->channel_id;
        });

        return $this->channelService->getCreatorsBatch($ids);
    }
}