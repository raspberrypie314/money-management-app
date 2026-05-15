@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <h2>Profil Saya</h2>

    <!-- Edit Nama -->
    <div>
        <h3>Edit Nama</h3>
        <form method="POST" action="{{ route('profil.name') }}">
            @csrf
            <div>
                <label>Nama:</label>
                <input type="text" name="name" value="{{ $user->name }}" required>
            </div>
            <button type="submit">Update Nama</button>
        </form>
    </div>

    <hr>

    <!-- Ganti Password -->
    <div>
        <h3>Ganti Password</h3>
        <form method="POST" action="{{ route('profil.password') }}">
            @csrf
            <div>
                <label>Password Saat Ini:</label>
                <input type="password" name="current_password" required>
            </div>
            <div>
                <label>Password Baru:</label>
                <input type="password" name="new_password" required>
            </div>
            <div>
                <label>Konfirmasi Password Baru:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit">Update Password</button>
        </form>
    </div>

    <hr>

    <!-- Hapus Akun -->
    <div>
        <h3 style="color: red;">Hapus Akun</h3>
        <p>Peringatan: Semua data kategori, transaksi, dan riwayat akan hilang permanen!</p>
        <form method="POST" action="{{ route('profil.account') }}" onsubmit="return confirm('Yakin ingin menghapus akun? Semua data akan hilang.')">
            @csrf
            @method('DELETE')
            <div>
                <label>Ketik "DELETE" untuk konfirmasi:</label>
                <input type="text" name="confirm_delete" required>
            </div>
            <button type="submit" style="color: red;">Hapus Akun Saya</button>
        </form>
    </div>
@endsection