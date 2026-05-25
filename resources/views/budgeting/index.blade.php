@extends('layouts.app')

@section('title', 'Budgeting - ' . ucfirst($mode))

@section('content')
<div class="budgeting-container">
    <div class="page-header">
        <h1><i class="fas fa-coins"></i> Budgeting - Manajemen {{ $mode == 'outcome' ? 'Pengeluaran' : 'Pemasukan' }}</h1>
    </div>

    <!-- Tombol Mode -->
    <div class="mode-toggle">
        <a href="{{ route('budgeting', ['mode' => 'outcome', 'sort' => $sort]) }}" class="mode-btn {{ $mode == 'outcome' ? 'active' : 'inactive' }}">
            <i class="fas fa-money-bill-wave"></i> Pengeluaran
        </a>
        <a href="{{ route('budgeting', ['mode' => 'income', 'sort' => $sort]) }}" class="mode-btn {{ $mode == 'income' ? 'active' : 'inactive' }}">
            <i class="fas fa-coins"></i> Pemasukan
        </a>
    </div>

    <!-- Tombol Terapkan ke Bulan Ini -->
    <form method="POST" action="{{ route('budgeting.apply') }}" class="apply-form">
        @csrf
        <button type="submit" class="btn-apply">
            <i class="fas fa-check-circle"></i> Terapkan ke Bulan Ini
        </button>
    </form>

    <!-- Tombol Sort -->
    <div class="sort-bar">
        <span class="sort-label"><i class="fas fa-sort"></i> Urutkan:</span>
        <div class="sort-buttons">
            <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => 'nama_asc']) }}" class="sort-btn {{ $sort == 'nama_asc' ? 'active' : '' }}">
                <i class="fas fa-sort-alpha-down"></i> Nama ↑
            </a>
            <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => 'nama_desc']) }}" class="sort-btn {{ $sort == 'nama_desc' ? 'active' : '' }}">
                <i class="fas fa-sort-alpha-up"></i> Nama ↓
            </a>
            <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => 'nilai_asc']) }}" class="sort-btn {{ $sort == 'nilai_asc' ? 'active' : '' }}">
                <i class="fas fa-arrow-down-1-9"></i> Nilai ↑
            </a>
            <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => 'nilai_desc']) }}" class="sort-btn {{ $sort == 'nilai_desc' ? 'active' : '' }}">
                <i class="fas fa-arrow-up-9-1"></i> Nilai ↓
            </a>
        </div>
    </div>

    <!-- Form Create -->
    <div class="form-card">
        <h3><i class="fas fa-plus-circle text-green"></i> Tambah {{ $mode == 'outcome' ? 'Pengeluaran' : 'Pemasukan' }} Baru</h3>
        <form method="POST" action="{{ route('budgeting') }}">
            @csrf
            <input type="hidden" name="category_type" value="{{ $mode }}">
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Nama Kategori</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Tipe</label>
                    <select name="type" required>
                        <option value="manual">Manual (sekali pakai)</option>
                        <option value="auto">Otomatis (berulang)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-money-bill"></i> Nilai</label>
                    <input type="number" name="value" step="1000" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-week"></i> Jangka (untuk otomatis)</label>
                    <select name="period">
                        <option value="">-- Tidak ada (manual) --</option>
                        <option value="weekly">Per Minggu</option>
                        <option value="monthly">Per Bulan</option>
                        <option value="yearly">Per Tahun</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-image"></i> Ikon (URL gambar)</label>
                    <input type="url" name="icon" placeholder="https://...">
                </div>
                @if($mode == 'income')
                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="is_primary_income" value="1">
                        <i class="fas fa-star"></i> Kas Utama (tidak dianggap tambahan kas masuk)
                    </label>
                </div>
                @endif
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Simpan</button>
                <button type="reset" class="btn-reset"><i class="fas fa-undo"></i> Reset</button>
            </div>
        </form>
    </div>

    <!-- Tabel Data -->
    <div class="table-card">
        <div class="table-header">
            <h3><i class="fas fa-list"></i> Daftar {{ $mode == 'outcome' ? 'Pengeluaran' : 'Pemasukan' }}</h3>
            <span class="badge">{{ $kategori->count() }} item</span>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> No</th>
                        <th><i class="fas fa-image"></i> Ikon</th>
                        <th><i class="fas fa-tag"></i> Nama</th>
                        <th><i class="fas fa-clock"></i> Tipe</th>
                        <th><i class="fas fa-money-bill"></i> Nilai</th>
                        <th><i class="fas fa-calendar"></i> Jangka</th>
                        <th><i class="fas fa-chart-line"></i> Total/Bulan</th>
                        <th><i class="fas fa-cogs"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($item->icon)
                                <img src="{{ $item->icon }}" class="category-icon" onerror="this.src='https://placehold.co/32x32/e2e8f0/64748b?text=Image'">
                            @else
                                <div class="icon-placeholder"><i class="fas fa-folder"></i></div>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->type == 'auto' ? 'Otomatis' : 'Manual' }}</td>
                        <td>Rp {{ number_format($item->value, 0, ',', '.') }}</td>
                        <td>{{ $item->period ?? '-' }}</td>
                        <td>Rp {{ number_format($item->monthly_value, 0, ',', '.') }}</td>
                        <td class="action-links">
                            <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => $sort, 'edit' => $item->id]) }}" class="edit-link">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('budgeting.destroy', $item->id) }}" class="delete-form" onsubmit="return confirm('Hapus kategori {{ $item->name }}? Semua data transaksi terkait akan terpengaruh.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-cell">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Belum ada data. Silakan tambah di atas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Edit (muncul jika ada parameter edit) -->
    @if(request('edit'))
        @php
            $editItem = $kategori->where('id', request('edit'))->first();
        @endphp
        @if($editItem)
        <div class="form-card edit-card">
            <h3><i class="fas fa-edit"></i> Edit {{ $editItem->name }}</h3>
            <form method="POST" action="{{ route('budgeting.update', $editItem->id) }}">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nama Kategori</label>
                        <input type="text" name="name" value="{{ $editItem->name }}" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-clock"></i> Tipe</label>
                        <select name="type" required>
                            <option value="manual" {{ $editItem->type == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="auto" {{ $editItem->type == 'auto' ? 'selected' : '' }}>Otomatis</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-money-bill"></i> Nilai</label>
                        <input type="number" name="value" step="1000" value="{{ $editItem->value }}" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-week"></i> Jangka</label>
                        <select name="period">
                            <option value="">-- Tidak ada --</option>
                            <option value="weekly" {{ $editItem->period == 'weekly' ? 'selected' : '' }}>Per Minggu</option>
                            <option value="monthly" {{ $editItem->period == 'monthly' ? 'selected' : '' }}>Per Bulan</option>
                            <option value="yearly" {{ $editItem->period == 'yearly' ? 'selected' : '' }}>Per Tahun</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Ikon (URL)</label>
                        <input type="url" name="icon" value="{{ $editItem->icon }}">
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => $sort]) }}" class="btn-cancel"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
        @endif
    @endif
</div>

<style>
.budgeting-container {
    max-width: 1280px;
    margin: 0 auto;
}

.page-header h1 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.mode-toggle {
    background: white;
    border-radius: 1rem;
    padding: 0.5rem;
    display: inline-flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.mode-btn {
    padding: 0.5rem 1.25rem;
    border-radius: 2rem;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.mode-btn.active {
    background: #10b981;
    color: white;
}

.mode-btn.inactive {
    background: #f1f5f9;
    color: #64748b;
}

.mode-btn.inactive:hover {
    background: #e2e8f0;
}

.apply-form {
    margin: 1rem 0;
}

.btn-apply {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.btn-apply:hover {
    background: #2563eb;
}

.sort-bar {
    background: white;
    border-radius: 1rem;
    padding: 0.75rem 1rem;
    margin: 1rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.sort-label {
    font-size: 0.75rem;
    color: #64748b;
}

.sort-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.sort-btn {
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-size: 0.75rem;
    background: #f1f5f9;
    color: #475569;
    transition: all 0.2s;
}

.sort-btn:hover, .sort-btn.active {
    background: #10b981;
    color: white;
}

.form-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    margin: 1rem 0;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.edit-card {
    border-left: 4px solid #10b981;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    font-size: 0.75rem;
    font-weight: 500;
    color: #334155;
    margin-bottom: 0.25rem;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-family: inherit;
}

.form-group input:focus, .form-group select:focus {
    outline: none;
    border-color: #10b981;
}

.checkbox-group {
    display: flex;
    align-items: center;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-group input {
    width: auto;
}

.form-buttons {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
}

.btn-save {
    background: #10b981;
    color: white;
    border: none;
    padding: 0.5rem 1.25rem;
    border-radius: 0.5rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-reset {
    background: #f1f5f9;
    color: #475569;
    border: none;
    padding: 0.5rem 1.25rem;
    border-radius: 0.5rem;
    cursor: pointer;
}

.btn-cancel {
    background: #f1f5f9;
    color: #475569;
    padding: 0.5rem 1.25rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.table-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    margin: 1rem 0;
}

.table-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h3 {
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.badge {
    background: #e2e8f0;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    font-size: 0.75rem;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    padding: 0.875rem 1rem;
    background: #f8fafc;
    font-weight: 600;
    font-size: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
}

td {
    padding: 0.875rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
}

tr:hover td {
    background: #fafcff;
}

.category-icon {
    width: 32px;
    height: 32px;
    border-radius: 0.5rem;
    object-fit: cover;
}

.icon-placeholder {
    width: 32px;
    height: 32px;
    border-radius: 0.5rem;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
}

.action-links {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.edit-link {
    color: #10b981;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.delete-form {
    display: inline;
}

.delete-btn {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.empty-cell {
    text-align: center;
}

.empty-state {
    padding: 3rem;
    text-align: center;
}

.empty-state i {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.text-green {
    color: #10b981;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .sort-bar {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection