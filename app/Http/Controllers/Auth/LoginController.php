<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if login input is an email or username
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'work_email' : 'username';

        // Attempt authentication
        if (Auth::attempt([$fieldType => $login, 'password' => $password], $request->filled('remember'))) {
            return redirect()->intended('/dashboard'); // Change to your intended route
        }

        // Return error message
        return back()->withErrors(['login' => 'Invalid credentials'])->withInput($request->only('login'));
    }
}