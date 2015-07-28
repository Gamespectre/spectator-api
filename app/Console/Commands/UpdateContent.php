<?php

namespace Spectator\Console\Commands;

use Illuminate\Console\Command;
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
     * Create a new command instance.
     *
     * @param ContentUpdate $admin
     */
    public function __construct(ContentUpdate $admin)
    {
        parent::__construct();
        $this->admin = $admin;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->admin->update();
    }
}
