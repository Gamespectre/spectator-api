<?php

namespace Spectator\Repositories;

use Spectator\User;

class UserRepository
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function findOrCreateUser($userData)
    {
        if ($authUser = $this->user->where('google_id', $userData->id)->first()) {
            return $authUser;
        }

        $name = is_null($userData->name) ? is_null($userData->nickname) ? $userData->user->snippet->title : $userData->nickname : $userData->name;

        return $this->user->create([
            'name' => $name,
            'email' => $userData->email,
            'google_id' => $userData->id,
            'avatar' => $userData->avatar,
            'token' => $userData->token
        ]);
    }
}