<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Redirect if already authenticated
        if (Auth::guard('admin')->check()) {
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

        // Debug: Cek apakah admin ada
        $admin = Admin::where('username', $credentials['username'])->first();
        
        if (!$admin) {
            Log::info('Admin not found: ' . $credentials['username']);
            return back()->withErrors([
                'username' => 'Username tidak ditemukan.',
            ])->withInput($request->except('password'));
        }

        Log::info('Admin found', [
            'username' => $admin->username,
            'password_starts_with' => substr($admin->password, 0, 10),
            'password_length' => strlen($admin->password)
        ]);

        // Cek password dengan berbagai metode
        $passwordMatch = false;

        // Method 1: Laravel Auth attempt
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $passwordMatch = true;
            Log::info('Login successful via Auth::attempt');
        }
        // Method 2: Manual hash check
        else if (Hash::check($credentials['password'], $admin->password)) {
            $passwordMatch = true;
            Log::info('Password correct via Hash::check');
            Auth::guard('admin')->login($admin, $request->boolean('remember'));
        }
        // Method 3: Plain text check (dan upgrade ke hash)
        else if ($credentials['password'] === $admin->password) {
            $passwordMatch = true;
            Log::info('Password correct via plain text - upgrading to hash');
            
            // Upgrade password ke hash
            $admin->password = Hash::make($credentials['password']);
            $admin->save();
            
            Auth::guard('admin')->login($admin, $request->boolean('remember'));
        }

        if ($passwordMatch) {
            $request->session()->regenerate();
            Log::info('Login successful for: ' . $admin->username);
            return redirect()->intended(route('dashboard'));
        }

        Log::warning('Login failed for: ' . $credentials['username']);
        
        return back()->withErrors([
            'username' => 'Username atau password tidak valid.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('status', 'Anda telah berhasil logout.');
    }
}