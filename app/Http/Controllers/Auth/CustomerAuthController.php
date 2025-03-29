<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:customers,email',
            'password'              => 'required|string|min:6|confirmed',
            'phone'                 => 'required|string|max:10|min:10',
            'address'               => 'required|string',
        ]);

        $customer = Customer::create([
            'name'      => $validatedData['name'],
            'email'     => $validatedData['email'],
            'password'  => Hash::make($validatedData['password']),
            'phone'     => $validatedData['phone'] ?? null,
            'address'   => $validatedData['address'] ?? null,
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->intended('/customer/dashboard');
    }

    public function showLoginForm()
    {
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/customer/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        return redirect('/customer/login');
    }
}
