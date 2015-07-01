<?php

namespace Spectator\Console\Commands;

use Spectator\Lib\Sources\GiantBombSource;
use Spectator\Lib\Repositories\GameRepository;
use Illuminate\Console\Command;

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
    protected $signature = 'spectator:create:game {query}';

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
        $data = $this->source->get('3030-' . $query);
        $model = $this->repo->createModel($data);

        $this->info('Game ' . $model->title . ' saved to the database!');
    }
}
