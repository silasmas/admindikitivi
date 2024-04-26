<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\ApiClientManager;

class AuthenticatedSessionController extends Controller
{
    public static $api_client_manager;
    public function __construct()
    {
        $this::$api_client_manager = new ApiClientManager();
    }
    /**
     * Display the login view.
     */
    public function create(): View
    {

        Session::put('url.intended', URL::previous());

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request)
    {
        $inputs = [
            'username' => $request->email,
            'password' => $request->password
        ];
        // Login API
        $user = $this::$api_client_manager::call('POST', getApiURL() . '/user/login', null, $inputs);
        //dd($user->data->api_token);
        $request->session()->put('tokenUserActive', $user->data->api_token);

        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
