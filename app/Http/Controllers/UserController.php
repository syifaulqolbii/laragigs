<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // show register page
    public function create()
    {
        return view('users.create');
    }

    // store a new user
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        // create user
        $user = User::create($formFields);

        // login user
        auth()->login($user);

        return redirect('/')->with('message', 'Your account has been created!');
    }

    // logout user
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');
    }

    // shoe login form
    public function login()
    {
        return view('users.login');
    }

    // login user
    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }
}
