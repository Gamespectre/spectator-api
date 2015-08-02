<?php

namespace Spectator\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Spectator\Services\App\AuthService;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @param AuthService $authService
     */
    public function __construct(Guard $auth, AuthService $authService)
    {
        $this->auth = $auth;
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check() && !$this->authService->userIs('anon')) {
            $user = $this->auth->user();
            $token = $this->authService->userSignedIn($user);

            return view('auth.loginMessage', ['user' => $user, 'token' => $token]);
        }

        return $next($request);
    }
}
