@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
    <p>Belum punya akun? <a href="{{ route('register') }}">Register</a></p>
@endsection