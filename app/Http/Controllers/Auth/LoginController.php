<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->except('_token'))) {
            return redirect()->route('products.index');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
