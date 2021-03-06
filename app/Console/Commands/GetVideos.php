<?php

namespace Spectator\Console\Commands;

use Illuminate\Console\Command;
use Spectator\Repositories\GameRepository;
use Spectator\Repositories\VideoRepository;
use Spectator\Repositories\YoutubeRepository;
use Spectator\Services\Youtube\YoutubeServiceManager;

class GetVideos extends Command
{
    private $repo;
    private $gameRepo;
    private $youtubeRepo;

    public function __construct(YoutubeRepository $youtube, GameRepository $gameRepo, VideoRepository $repo) {
        parent::__construct();

        $this->gameRepo = $gameRepo;
        $this->youtubeRepo = $youtube;
        $this->repo = $repo;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spectator:get:videos {game} {--force}';

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
        $gameId = (int) $this->argument('game');
        $game = $this->gameRepo->get($gameId);
        /*$data = $this->api->searchYoutubeContent($game->title . " lets play", 5, $this->option('force'));

        $this->youtubeRepo->saveAll($data, $game);

        $this->info('Saved fresh data.');
        */
    }
}
