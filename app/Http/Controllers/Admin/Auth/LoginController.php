<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                return redirect()->route('admin.products.index');
            }

            return back()->withErrors([
                'email' => 'Invalid login credentials',
            ])->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to process login: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again.',
            ])->withInput();
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return redirect()->route('login');
        } catch (\Exception $e) {
            Log::error('Failed to logout: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Unable to logout. Please try again.');
        }
    }
}
