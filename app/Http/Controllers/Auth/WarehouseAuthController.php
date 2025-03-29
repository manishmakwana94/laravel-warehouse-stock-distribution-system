<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\WarehouseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WarehouseAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.warehouse-register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:warehouse_users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);

        $warehouseUser = WarehouseUser::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone'    => $validatedData['phone'] ?? null,
        ]);

        Auth::guard('warehouse')->login($warehouseUser);
        return redirect()->intended('/warehouse/dashboard');
    }


    // Show the warehouse user login form
    public function showLoginForm()
    {
        return view('auth.warehouse-login');
    }

    // Process the warehouse user login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('warehouse')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/warehouse/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ]);
    }

    // Logout for warehouse user
    public function logout(Request $request)
    {
        Auth::guard('warehouse')->logout();
        return redirect('/warehouse/login');
    }
}
