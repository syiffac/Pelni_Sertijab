<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleSelectionController extends Controller
{
    /**
     * Show role selection page
     */
    public function index()
    {
        // Jika sudah login sebagai admin, redirect ke dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        
        return view('role-selection');
    }

    /**
     * Handle role selection
     */
    public function selectRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,puk'
        ]);

        $role = $request->input('role');

        if ($role === 'admin') {
            return redirect()->route('login');
        } elseif ($role === 'puk') {
            return redirect()->route('puk.dashboard');
        }

        return redirect()->route('role.selection')->with('error', 'Role tidak valid');
    }
}
