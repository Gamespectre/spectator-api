<?php


namespace Spectator\Services\App;


use Auth;
use JWTFactory;
use JWTAuth;

class AuthService
{
    public function __construct()
    {
        //
    }

    public function createToken()
    {
        $meta = ['auth' => 'anon'];

        if(Auth::check()) {
            $meta['auth'] = 'user';
            $token = JWTAuth::fromUser(Auth::user(), $meta);
        }
        else {
            $payload = JWTFactory::make($meta);
            $token = JWTAuth::encode($payload)->get();
        }

        return $token;
    }

    public function queryToken($token)
    {
        $payload = $token->getPayload();
        $auth = $payload->get('auth');

        if($auth !== 'anon' && $user = $this->authenticateTokenUser($token)) {

            // TODO: Return user level as auth type

            return [
                'user' => $user,
                'auth' => 'user'
            ];
        }

        return [
            'user' => false,
            'auth' => 'anon'
        ];
    }

    public function authenticateTokenUser($token)
    {
        return $token->authenticate();
    }
}