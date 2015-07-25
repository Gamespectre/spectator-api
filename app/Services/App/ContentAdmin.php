<?php

namespace Spectator\Services\App;

use Spectator\Events\PackageSaveStarted;
use Spectator\Events\PackageStarted;
use Spectator\Exceptions\PackageNotFoundException;
use Spectator\Repositories\GameRepository;

class ContentAdmin {

    /**
     * @var AuthService
     */
    private $auth;
    /**
     * @var GameRepository
     */
    private $game;

    public function __construct(AuthService $auth, GameRepository $game)
    {

        $this->auth = $auth;
        $this->game = $game;
    }

    private function getChannelName($base)
    {
        return uniqid() . '-' . $base;
    }

    public function getPackageData($id)
    {
        $packageData = \Cache::get($id);

        if(is_null($packageData)) {
            throw new PackageNotFoundException("Your package timed out, or might have never existed.");
        }

        $package = unserialize($packageData);
        return $package->getData()->toArray();
    }

    public function savePackage($id, $data)
    {
        $packageData = \Cache::get($id);
        $channel = $this->getChannelName('packagesave');

        if(is_null($packageData)) {
            throw new PackageNotFoundException("Your package timed out, or might have never existed.");
        }

        $package = unserialize($packageData);
        $package->setChannel($channel);
        $package->update($data);

        event(new PackageSaveStarted($package));

        return $channel;
    }

    public function addGame($query)
    {
        $channel = $this->getChannelName('gameadd');

        $package = GamePackage::create([
            'channel' => $channel,
            'query' => $query,
            'resource' => ['name' => 'game', 'method' => 'add']
        ]);

        event(new PackageStarted($package));

        return $channel;
    }

    public function searchGame($query)
    {
        $channel = $this->getChannelName('gamesearch');

        $package = GamePackage::create([
            'channel' => $channel,
            'query' => $query,
            'resource' => ['name' => 'game', 'method' => 'search']
        ]);

        event(new PackageStarted($package));

        return $channel;
    }

    public function searchContent($query, $resource)
    {
        $channel = $this->getChannelName('contentsearch');

        $package = YoutubePackage::create([
            'channel' => $channel,
            'resource' => ['name' => $resource, 'method' => 'search'],
            'query' => $query
        ]);

        event(new PackageStarted($package));

        return $channel;
    }

    public function addContent($id, $resource)
    {
        $channel = $this->getChannelName('contentadd');

        $package = YoutubePackage::create([
            'channel' => $channel,
            'resource' => ['name' => $resource, 'method' => 'add'],
            'query' => $id
        ]);

        event(new PackageStarted($package));

        return $channel;
    }
}