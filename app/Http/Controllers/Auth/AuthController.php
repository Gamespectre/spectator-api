<?php

namespace Spectator\Http\Controllers\Auth;

use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use JWTAuth;
use Socialite;
use Spectator\Http\Controllers\Controller;
use Spectator\Repositories\UserRepository;
use JWTFactory;
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startLogin(Request $request)
    {
        $channel = 'userlogin-' . uniqid();
        session(['channel' => $channel]);

        return response()->json([
            'success' => true,
            'channel' => $channel
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
        $this->authService->userSignedIn($authUser);

        return $authUser->name . " logged in successfully. This window will close in a moment.";
    }
}
