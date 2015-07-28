<?php

namespace Spectator\Http\Middleware;

use Spectator\Services\App\AuthService;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class RequireAdmin extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $authService = \App::make(AuthService::class);

        if(!$authService->userIs('admin')) {
            return response()->json(['success' => false, 'error' => 'User privileges not sufficient.']);
        }

        return $next($request);
    }
}
