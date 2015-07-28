<?php

use Illuminate\Database\Seeder;
use Spectator\Repositories\UserRepository;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repo = \App::make(UserRepository::class);

        $user = $repo->createFromArray([
            'google_id' => "0",
            'name' => 'Anonymous',
            'email' => null,
            'avatar' => 'none',
            'token' => 'none'
        ]);

        $repo->setRole($user, ['anon']);
    }
}
