<?php

namespace App\Http\Controllers;

use App\Models\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // ================= REGISTER =================
   public function register(Request $request)
{
    $request->validate(
        [
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:authentication,email',
            'password' => 'required|min:6'
        ],
        [
            'name.required'     => 'Name is required',
            'name.min'          => 'Name must be at least 3 characters',

            'email.required'   => 'Email address is required',
            'email.email'      => 'Please enter a valid email address',
            'email.unique'     => 'This email is already registered',

            'password.required'=> 'Password is required',
            'password.min'     => 'Password must be at least 6 characters'
        ]
    );

    Authentication::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password)
    ]);

    return redirect('/login')->with('success', 'Registration successful. Please login.');
}

    // ================= LOGIN =================
public function login(Request $request)
{
    $request->validate(
        [
            'email'    => 'required|email',
            'password' => 'required'
        ],
        [
            'email.required'    => 'Email is required',
            'email.email'       => 'Enter a valid email address',
            'password.required' => 'Password is required'
        ]
    );

    $user = Authentication::where('email', $request->email)->first();

    if (!$user) {
        return back()->with('error', 'This email is not registered');
    }

    if (!Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Password does not match');
    }

    $seconds = 159;

    Session::put('user_id', $user->id);
    Session::put('user_name', $user->name);
    Session::put('session_expires_at', time() + $seconds);

    return redirect('/dashboard');
}




    // ================= DASHBOARD =================
   public function dashboard()
{
    if (!Session::has('user_id')) {
        return redirect('/login');
    }

    if (time() > Session::get('session_expires_at')) {
        Session::flush();
        return redirect('/login')->with('session_expired', true);
    }

    return view('dashboard');
}


    // ================= LOGOUT =================
    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
