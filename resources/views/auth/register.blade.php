@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h2>Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Nama:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Konfirmasi Password:</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
    <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
@endsection