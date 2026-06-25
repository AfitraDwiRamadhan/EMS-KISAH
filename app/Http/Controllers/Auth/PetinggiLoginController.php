<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PetinggiLoginController extends Controller
{
    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::guard('petinggi')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            Log::info('User authenticated successfully. Redirecting to admin.dashboard');
            return redirect()->intended(route('admin.dashboard'));
        }

        Log::warning('Authentication failed for user: ' . $request->username);
        return back()->withErrors([
            'username' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('petinggi')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}