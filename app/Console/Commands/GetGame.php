<?php

namespace Spectator\Console\Commands;

use Illuminate\Console\Command;
use Spectator\Events\Game\Search;
use Spectator\Repositories\GameRepository;
use Spectator\Sources\GiantBombSource;

class GetGame extends Command
{
    private $source;
    private $repo;

    public function __construct(GiantBombSource $source, GameRepository $repo) {
        parent::__construct();

        $this->source = $source;
        $this->repo = $repo;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spectator:get:game {query}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get game data for Giant Bomb ID';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = $this->argument('query');

        \Event::fire(new Search([
            'gameApiId' => $query
        ]));

        $this->info('Game ' . $model->title . ' saved to the database!');
    }
}
