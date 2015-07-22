<?php


namespace Spectator\Services\App;


use Auth;
use JWTFactory;
use JWTAuth;
use Spectator\Role;
use Spectator\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth as JWT;
use Spectator\Events\UserSignedIn;

class AuthService
{
    /**
     * @var JWT
     */
    private $jwt;

    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    public function createToken()
    {
        if(Auth::check()) {
            $token = JWTAuth::fromUser(Auth::user());
        }
        else {
            Auth::login(Role::where('level', 'anon')->first()->users()->first());
            $token = JWTAuth::fromUser(Auth::user());
        }

        return $token;
    }
    public function queryToken()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user->load('roles');
    }

    public function userSignedIn(User $user)
    {
        Auth::login($user, true);

        $channel = session('channel');
        $token = $this->createToken();

        event(new UserSignedIn([
            'user' => $user->load('roles'),
            'token' => $token
        ], $channel));
    }

    public function userIs($level) {
        $user = Auth::user();
        return !$user->roles()->where('level', $level)->get()->isEmpty();
    }
}