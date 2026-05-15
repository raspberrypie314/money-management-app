<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }
    
    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        $user = User::where('username', $request->username)->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            session(['user_id' => $user->id, 'user_name' => $user->name]);
            return redirect()->route('dashboard');
        }
        
        return back()->with('error', 'Username atau password salah');
    }
    
    // Tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }
    
    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);
        
        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);
        
        session(['user_id' => $user->id, 'user_name' => $user->name]);
        return redirect()->route('dashboard');
    }
    
    // Logout
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}