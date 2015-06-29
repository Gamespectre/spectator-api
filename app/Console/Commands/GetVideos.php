<?php

namespace Spectator\Console\Commands;

use Spectator\Lib\Repositories\GameRepository;
use Spectator\Lib\Repositories\VideoRepository;
use Spectator\Lib\Services\YoutubeResourcesCreator;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class GetVideos extends Command
{
    private $repo;
    private $api;
    private $gameRepo;

    public function __construct(YoutubeResourcesCreator $api, GameRepository $gameRepo, VideoRepository $repo) {
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
    protected $signature = 'spectator:get:videos {gameIdentifier} {--api} {--name}';

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
        $query = $this->argument('gameIdentifier');

        if($this->option('api') && $this->option('name')) {
            $this->error('Only supply the "api" OR "name" option.');
            return false;
        }

        $game = $this->getGameTitle($query, $this->option('api'), $this->option('name'));

        $data = $this->api->startWithtextQuery($game . ' lets play');
        $model = $this->repo->createModel($data);

        $this->info('Videos for ' . $game . ' saved to the database!');
    }

    private function getGameTitle($identifier, $apiOption, $nameOption)
    {
        $game = null;

        if($apiOption) {
            $game = $this->gameRepo->getByApi($identifier);
        }
        else if($nameOption) {
            $game = $this->gameRepo->getByName($identifier);
        }
        else {
            $game = $this->gameRepo->get($identifier);
        }

        if($game !== null) {
            return $game->title;
        }

        return false;
    }
}
