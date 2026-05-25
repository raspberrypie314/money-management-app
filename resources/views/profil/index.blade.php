@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="profil-full">
    <div class="page-header">
        <h1><i class="fas fa-user-circle"></i> Profil Saya</h1>
    </div>

    <div class="profil-grid">
        <!-- Edit Nama Card -->
        <div class="profil-card">
            <div class="card-header">
                <div class="card-icon green-bg">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h3>Edit Nama</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profil.name') }}">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Update Nama
                    </button>
                </form>
            </div>
        </div>

        <!-- Ganti Password Card -->
        <div class="profil-card">
            <div class="card-header">
                <div class="card-icon yellow-bg">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>Ganti Password</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profil.password') }}">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-key"></i> Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password Baru</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-check-circle"></i> Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Hapus Akun Card -->
        <div class="profil-card danger-card">
            <div class="card-header">
                <div class="card-icon red-bg">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h3>Hapus Akun</h3>
            </div>
            <div class="card-body">
                <div class="alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Peringatan: Semua data kategori, transaksi, dan riwayat akan hilang permanen!</span>
                </div>
                <form method="POST" action="{{ route('profil.account') }}" onsubmit="return confirm('Yakin ingin menghapus akun? Semua data akan hilang dan tidak bisa dikembalikan.')">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <label><i class="fas fa-exclamation-circle"></i> Ketik "DELETE" untuk konfirmasi</label>
                        <input type="text" name="confirm_delete" class="form-control" placeholder="DELETE" required>
                    </div>
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash-alt"></i> Hapus Akun Saya
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
* {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
}

.profil-full {
    width: 100%;
    padding: 20px 24px;
    background: #f5f7fa;
    min-height: calc(100vh - 60px);
}

.page-header h1 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.profil-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

@media (max-width: 768px) {
    .profil-full {
        padding: 16px;
    }
    .profil-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

.profil-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.card-header {
    padding: 20px 24px;
    background: #fafbfc;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 14px;
}

.card-header h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.card-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.green-bg {
    background: #dcfce7;
    color: #10b981;
}

.yellow-bg {
    background: #fef3c7;
    color: #d97706;
}

.red-bg {
    background: #fee2e2;
    color: #ef4444;
}

.card-body {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #444;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-control {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #ddd;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
}

.btn-primary {
    background: #10b981;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 12px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-primary:hover {
    background: #059669;
}

.alert-danger {
    background: #fef2f2;
    border-left: 4px solid #ef4444;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #991b1b;
}

.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 12px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-danger:hover {
    background: #dc2626;
}

.danger-card {
    border: 1px solid #fee2e2;
}
</style>
@endsection