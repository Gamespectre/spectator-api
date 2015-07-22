<?php

namespace Spectator\Console\Commands;

use Illuminate\Console\Command;
use Spectator\Repositories\UserRepository;
use Spectator\Role;

class CreateAnonUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:anon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an anon user';
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = $this->user->createFromArray([
            'google_id' => "0",
            'name' => 'Anonymous',
            'email' => null,
            'avatar' => 'none',
            'token' => 'none'
        ]);

        $this->user->setRole($user, ['anon']);

        $this->info('Anon user created');
    }
}
