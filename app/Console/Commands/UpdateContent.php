<?php

namespace Spectator\Console\Commands;

use Illuminate\Console\Command;
use Spectator\Processing\Series\Update;
use Spectator\Services\App\ContentUpdate;

set_time_limit(0);

class UpdateContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gamespectre:content:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';
    /**
     * @var Update
     */
    private $update;

    /**
     * Create a new command instance.
     *
     * @param Update $update
     */
    public function __construct(Update $update)
    {
        parent::__construct();
        $this->update = $update;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->update->execute();
    }
}
