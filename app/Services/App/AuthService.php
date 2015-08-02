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
            $anonAccount = Role::where('level', 'anon')->first()->users()->first();

            if(is_null($anonAccount)) {
                // Shouldn't happen except if the db is wiped...
                \Artisan::call('user:anon');
                $anonAccount = Role::where('level', 'anon')->first()->users()->first();
            }

            Auth::login($anonAccount);
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
        return $this->createToken();
    }

    public function userIs($level) {
        $user = Auth::user();
        $roles = $user->roles->where('level', $level);

        return !$roles->isEmpty();
    }
}