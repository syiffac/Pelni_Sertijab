<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);

        // Debug: Cek apakah user ada
        $user = User::where('username', $credentials['username'])->first();
        
        if (!$user) {
            Log::info('User not found: ' . $credentials['username']);
            return back()->withErrors([
                'username' => 'Username tidak ditemukan.',
            ])->withInput($request->except('password'));
        }

        Log::info('User found', [
            'username' => $user->username,
            'password_starts_with' => substr($user->password, 0, 10),
            'password_length' => strlen($user->password)
        ]);

        // Cek password dengan berbagai metode
        $passwordMatch = false;

        // Method 1: Laravel Auth attempt
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $passwordMatch = true;
            Log::info('Login successful via Auth::attempt');
        }
        // Method 2: Manual hash check
        else if (Hash::check($credentials['password'], $user->password)) {
            $passwordMatch = true;
            Log::info('Password correct via Hash::check');
            Auth::login($user, $request->boolean('remember'));
        }
        // Method 3: Plain text check (dan upgrade ke hash)
        else if ($credentials['password'] === $user->password) {
            $passwordMatch = true;
            Log::info('Password correct via plain text - upgrading to hash');
            
            // Upgrade password ke hash
            $user->password = Hash::make($credentials['password']);
            $user->save();
            
            Auth::login($user, $request->boolean('remember'));
        }

        if ($passwordMatch) {
            $request->session()->regenerate();
            Log::info('Login successful for: ' . $user->username);
            return redirect()->intended(route('dashboard'));
        }

        Log::warning('Login failed for: ' . $credentials['username']);
        
        return back()->withErrors([
            'username' => 'Username atau password tidak valid.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('status', 'Anda telah berhasil logout.');
    }
}