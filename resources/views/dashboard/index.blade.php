@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-full">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon green-bg">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Saldo Efektif</div>
                <div class="stat-value text-green">Rp {{ number_format($saldoEfektif, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green-light-bg">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Pemasukan</div>
                <div class="stat-value text-green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red-light-bg">
                <i class="fas fa-arrow-up"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Pengeluaran</div>
                <div class="stat-value text-red">Rp {{ number_format($totalOutcome, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="controls">
        <div class="control-group">
            <span class="control-label">Mode</span>
            <div class="control-buttons">
                <a href="{{ route('dashboard', ['mode' => 'outcome', 'sort' => $sort]) }}" class="filter-btn {{ $mode == 'outcome' ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> Pengeluaran
                </a>
                <a href="{{ route('dashboard', ['mode' => 'income', 'sort' => $sort]) }}" class="filter-btn {{ $mode == 'income' ? 'active' : '' }}">
                    <i class="fas fa-coins"></i> Pemasukan
                </a>
            </div>
        </div>
        <div class="control-group">
            <span class="control-label">Urutkan</span>
            <div class="control-buttons">
                <a href="{{ route('dashboard', ['mode' => $mode, 'sort' => 'nama_asc']) }}" class="sort-btn"><i class="fas fa-sort-alpha-down"></i> Nama A-Z</a>
                <a href="{{ route('dashboard', ['mode' => $mode, 'sort' => 'nama_desc']) }}" class="sort-btn"><i class="fas fa-sort-alpha-up"></i> Nama Z-A</a>
                <a href="{{ route('dashboard', ['mode' => $mode, 'sort' => 'nilai_asc']) }}" class="sort-btn"><i class="fas fa-arrow-up"></i> Nilai ↑</a>
                <a href="{{ route('dashboard', ['mode' => $mode, 'sort' => 'nilai_desc']) }}" class="sort-btn"><i class="fas fa-arrow-down"></i> Nilai ↓</a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-header">
            <h3>
                <i class="fas {{ $mode == 'outcome' ? 'fa-shopping-cart' : 'fa-hand-holding-usd' }}"></i>
                {{ $mode == 'outcome' ? 'Pengeluaran' : 'Pemasukan Non Kas Utama' }}
            </h3>
            <span class="badge">{{ $kategori->count() }} item</span>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-image"></i> Ikon</th>
                        <th><i class="fas fa-tag"></i> Nama</th>
                        <th><i class="fas fa-money-bill"></i> Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $item)
                    <tr>
                        <td>
                            @if($item->icon)
                                <img src="{{ $item->icon }}" class="img-icon">
                            @else
                                <i class="fas fa-folder"></i>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td class="{{ $mode == 'outcome' ? 'text-red' : 'text-green' }}">
                            <i class="fas {{ $mode == 'outcome' ? 'fa-minus-circle' : 'fa-plus-circle' }}"></i>
                            Rp {{ number_format($item->value, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            <i class="fas fa-folder-open"></i>
                            <p>Belum ada data</p>
                            <a href="{{ route('budgeting', ['mode' => $mode]) }}" class="btn-add">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
* {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
}

.dashboard-full {
    width: 100%;
    padding: 20px 24px;
    background: #f5f7fa;
    min-height: calc(100vh - 60px);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .dashboard-full {
        padding: 16px;
    }
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.green-bg {
    background: #d1fae5;
    color: #059669;
}

.stat-icon.green-light-bg {
    background: #dcfce7;
    color: #10b981;
}

.stat-icon.red-light-bg {
    background: #fee2e2;
    color: #ef4444;
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 22px;
    font-weight: 700;
}

.controls {
    background: white;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.control-group {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.control-label {
    font-size: 13px;
    font-weight: 600;
    color: #555;
}

.control-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-btn, .sort-btn {
    padding: 6px 18px;
    border-radius: 24px;
    text-decoration: none;
    font-size: 13px;
    background: #f0f2f5;
    color: #444;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.filter-btn.active {
    background: #10b981;
    color: white;
}

.filter-btn:hover, .sort-btn:hover {
    background: #10b981;
    color: white;
}

.table-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.table-header {
    padding: 16px 20px;
    background: #fafbfc;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h3 {
    font-size: 16px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.badge {
    background: #e2e8f0;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: #555;
}

.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    padding: 14px 16px;
    background: white;
    font-size: 13px;
    font-weight: 600;
    color: #444;
    border-bottom: 1px solid #eee;
}

td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
}

tr:hover td {
    background: #fafafa;
}

.img-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    object-fit: cover;
}

.text-green {
    color: #10b981;
    font-weight: 600;
}

.text-red {
    color: #ef4444;
    font-weight: 600;
}

.text-center {
    text-align: center;
}

.btn-add {
    display: inline-block;
    background: #10b981;
    color: white;
    padding: 8px 24px;
    border-radius: 24px;
    text-decoration: none;
    font-size: 13px;
    margin-top: 12px;
}
</style>
@endsection