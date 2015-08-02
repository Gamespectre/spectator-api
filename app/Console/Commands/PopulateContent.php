<?php

namespace Spectator\Console\Commands;

use Illuminate\Console\Command;
use Spectator\Processing\Series\Populate;
use Spectator\Services\App\ContentUpdate;

set_time_limit(0);

class PopulateContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gamespectre:content:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * @var Populate
     */
    private $populate;

    /**
     * Create a new command instance.
     *
     * @param Populate $populate
     * @internal param ContentUpdate $admin
     */
    public function __construct(Populate $populate)
    {
        parent::__construct();
        $this->populate = $populate;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->populate->execute();
    }
}
