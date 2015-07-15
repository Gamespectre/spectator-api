<?php

namespace Spectator\Console\Commands;

use Illuminate\Console\Command;
use Spectator\User;

class CreateBaseUser extends Command
{
    private $source;
    private $repo;
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user) {
        parent::__construct();
        $this->user = $user;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quickly create a user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = is_null($this->argument('password')) ? $this->argument('password') : 'password';

        $user = $this->user->firstOrCreate([
            'name' => is_null($name) ? 'admin' : $name,
            'email' => is_null($email) ? 'dunderfeltdaniel@gmail.com' : $email,
            'password' => bcrypt($password),
        ]);

        $this->info('User ' . $user->name . ' created!');
    }
}
