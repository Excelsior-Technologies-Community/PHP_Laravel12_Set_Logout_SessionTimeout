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
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:authentication',
            'password' => 'required|min:6'
        ]);

        Authentication::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login')->with('success', 'Registration successful');
    }

    // ================= LOGIN =================
public function login(Request $request)
{
    $user = Authentication::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Email or Password is incorrect');
    }

    $seconds = 35; // ⏱️ session duration (changeable)

    Session::put('user_id', $user->id);
    Session::put('user_name', $user->name);

    // 🔥 expiry timestamp store
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
