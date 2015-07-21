<?php

namespace Spectator\Http\Controllers\Auth;

use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use JWTAuth;
use Spectator\Events\UserSignedIn;
use Spectator\Http\Controllers\Controller;
use Spectator\Repositories\UserRepository;
use JWTFactory;
use Spectator\Services\App\AuthService;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

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
        $this->middleware('guest', ['except' => [
            'getLogout',
            'user',
            'startLogin',
            'token',
            'query'
        ]]);

        $this->user = $user;
        $this->authService = $authService;
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
        $token = JWTAuth::parseToken();
        $user = $this->authService->queryToken($token);

        return response()->json([
            'success' => true,
            'user' => $user['user'],
            'auth' => $user['auth']
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

    public function user()
    {
        $channel = session('channel');
        $token = $this->authService->createToken();
        $user = Auth::user();

        // TODO: Set auth to user level

        event(new UserSignedIn([
            'user' => $user,
            'token' => $token,
            'auth' => 'user'
        ], $channel));

        return "User signed in. Channel: " . $channel;
    }

    public function redirectToProvider()
    {
        return \Socialite::with('youtube')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = \Socialite::with('youtube')->user();
        } catch (Exception $e) {
            return redirect('api/auth/youtube');
        }

        $authUser = $this->user->findOrCreateUser($user);

        Auth::login($authUser, true);

        return redirect('api/auth/user');
    }
}
