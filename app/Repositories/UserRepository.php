<?php

namespace Spectator\Repositories;

use Spectator\Role;
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

    public function createFromArray(array $userData)
    {
        return $this->user->firstOrCreate($userData);
    }

    public function setRole(User $user, array $role)
    {
        $roleModels = Role::whereIn('level', $role)->get();

        $user->roles()->sync($roleModels->map(function($role) {
            return $role->id;
        })->toArray());

        return $user;
    }
}