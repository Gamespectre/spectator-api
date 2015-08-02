<?php

namespace Spectator\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Socialite;
use Spectator\Http\Controllers\Controller;
use Spectator\Repositories\UserRepository;
use Spectator\Services\App\AuthService;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    /**
     * @var UserRepository
     */
    private $user;
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(UserRepository $user, AuthService $authService)
    {
        $this->user = $user;
        $this->authService = $authService;

        $this->middleware('guest', ['only' => [
            'redirectToProvider',
            'handleProviderCallback'
        ]]);
    }

    public function token()
    {
        $token = $this->authService->createToken();

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }

    public function query()
    {
        $user = $this->authService->queryToken();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function redirectToProvider()
    {
        return Socialite::with('youtube')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::with('youtube')->user();
        } catch (Exception $e) {
            return redirect('api/auth/youtube');
        }

        $authUser = $this->user->findOrCreateUser($user);
        $token = $this->authService->userSignedIn($authUser);

        return view('auth.loginMessage', ['user' => $authUser, 'token' => $token]);
    }
}
