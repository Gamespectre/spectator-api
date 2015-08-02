<?php

namespace Spectator\Jobs;

use App;
use Illuminate\Support\Collection;
use League\Pipeline\Pipeline;
use Spectator\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spectator\Series;

class Asyncify extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var Series
     */
    public $data;
    /**
     * @var Pipeline
     */
    public $pipeline;

    /**
     * Create a new job instance.
     *
     * @param Collection $data
     * @param Pipeline $pipeline
     */
    public function __construct(Collection $data, Pipeline $pipeline)
    {
        $this->data = $data;
        $this->pipeline = $pipeline;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->pipeline->process($this->data);
    }
}
