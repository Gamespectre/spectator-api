<?php

namespace Spectator\Console\Commands;

use Spectator\Lib\Repositories\GameRepository;
use Spectator\Lib\Repositories\VideoRepository;
use Spectator\Lib\Services\Youtube\YoutubeResourcesManager;
use Illuminate\Console\Command;

class GetVideos extends Command
{
    private $repo;
    private $api;
    private $gameRepo;

    public function __construct(YoutubeResourcesManager $api, GameRepository $gameRepo, VideoRepository $repo) {
        parent::__construct();

        $this->gameRepo = $gameRepo;
        $this->api = $api;
        $this->repo = $repo;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spectator:get:videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Lets play videos and playlists for game';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->api->getYoutubeContent("witcher 3 lets play", $this);
        $this->info('Got data!');
    }
}
