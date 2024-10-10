<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    protected $authService;
    const LOGIN_FAILED_MESSAGE = 'Login failed. Please check your email and password.';


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {

        $credentials = $request->validated();

        if ($this->authService->attemptLogin($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }


        return back()->withErrors([
            'email' => self::LOGIN_FAILED_MESSAGE,
            'password' => self::LOGIN_FAILED_MESSAGE,
        ]);
    }


    public function logout(Request $request)
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
