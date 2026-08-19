<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('my-account');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                Auth::logout();

                return redirect()->route('admin.login')->with('error', 'Administrators must log in via the Admin Portal.');
            }

            if (! $user->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('my-account'))->with('success', 'Welcome back, '.$user->name);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function adminLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('my-account');
        }

        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                if (! $user->is_active) {
                    Auth::logout();

                    return back()->withErrors([
                        'email' => 'Your administrator account has been deactivated.',
                    ])->onlyInput('email');
                }

                $request->session()->regenerate();

                return redirect()->route('admin.dashboard')->with('success', 'Logged in to Admin Portal successfully.');
            }

            Auth::logout();

            return back()->withErrors([
                'email' => 'Access denied. Customers are not permitted to access this portal.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function registerForm()
    {
        if (Auth::check()) {
            return redirect()->route('my-account');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Verify email deliverability via ZeroBounce (fail open on errors)
        try {
            $verification = Http::timeout(8)->get('https://api.zerobounce.net/v2/validate', [
                'api_key' => config('services.zerobounce.key'),
                'email' => $request->email,
                'ip_address' => '',
            ]);

            if ($verification->successful() && $verification->json('status') === 'invalid') {
                return back()->withErrors([
                    'email' => 'This email address appears to be invalid or undeliverable. Please use a real email.',
                ])->onlyInput('email');
            }
        } catch (\Exception $e) {
            // API unavailable — fail open and allow registration to proceed
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('my-account')->with('success', 'Account registered and logged in successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
