@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="riwayat-full">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="fas fa-history"></i> Riwayat Transaksi</h1>
    </div>

    <!-- Saldo Card -->
    <div class="saldo-card">
        <div class="saldo-icon">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="saldo-info">
            <span class="saldo-label">Total Saldo Tersedia</span>
            <h2 class="saldo-value">Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</h2>        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('riwayat') }}" class="filter-form">
            <div class="filter-group">
                <label><i class="fas fa-calendar-alt"></i> Tipe Laporan</label>
                <select name="view_type" class="filter-select">
                    <option value="monthly" {{ $viewType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $viewType == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            
            @if($viewType == 'monthly')
            <div class="filter-group">
                <label><i class="fas fa-calendar-month"></i> Bulan</label>
                <input type="text" name="month" placeholder="MM" value="{{ $month }}" class="filter-input" maxlength="2">
            </div>
            @endif
            
            <div class="filter-group">
                <label><i class="fas fa-calendar-year"></i> Tahun</label>
                <input type="text" name="year" placeholder="YYYY" value="{{ $year }}" class="filter-input" maxlength="4">
            </div>
            
            <div class="filter-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Statistik Kas -->
    <div class="stats-kas">
        <div class="kas-card kas-masuk">
            <div class="kas-icon"><i class="fas fa-arrow-down"></i></div>
            <div class="kas-info">
                <span class="kas-label">Total Kas Masuk</span>
                <h3 class="kas-value text-green">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="kas-card kas-keluar">
            <div class="kas-icon"><i class="fas fa-arrow-up"></i></div>
            <div class="kas-info">
                <span class="kas-label">Total Kas Keluar</span>
                <h3 class="kas-value text-red">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Daftar Transaksi -->
    <div class="transaksi-card">
        <div class="card-header">
            <h3><i class="fas fa-list-ul"></i> Daftar Transaksi</h3>
            <span class="badge">{{ $transactions->count() }} transaksi</span>
        </div>
        
        @if($transactions->count() > 0)
            <div class="transaksi-list">
                @foreach($transactions as $item)
                <div class="transaksi-item">
                    <div class="transaksi-left">
                        <div class="transaksi-icon {{ $item->type }}">
                            <i class="fas {{ $item->type == 'income' ? 'fa-plus-circle' : 'fa-minus-circle' }}"></i>
                        </div>
                        <div class="transaksi-detail">
                            <div class="transaksi-kategori">
                                {{ $item->category->name ?? 'Kategori tidak ditemukan' }}
                                @if($item->category && $item->category->type == 'auto')
                                    <span class="badge-auto"><i class="fas fa-sync-alt"></i> Otomatis</span>
                                @else
                                    <span class="badge-manual"><i class="fas fa-hand-paper"></i> Manual</span>
                                @endif
                            </div>
                            <div class="transaksi-desc">
                                @if($item->type == 'income')
                                    <span class="text-green">+ Rp {{ number_format($item->amount, 0, ',', '.') }}</span> ditambahkan pada arus kas masuk
                                @else
                                    <span class="text-red">- Rp {{ number_format($item->amount, 0, ',', '.') }}</span> ditambahkan pada arus kas keluar
                                @endif
                            </div>
                            <div class="transaksi-date">
                                <i class="fas fa-calendar-alt"></i> {{ date('d/m/Y', strtotime($item->transaction_date)) }}
                            </div>
                        </div>
                    </div>
                    <div class="transaksi-right">
                        <form method="POST" action="{{ route('riwayat.destroy', $item->id) }}" onsubmit="return confirm('Hapus transaksi ini? Data tidak bisa dikembalikan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>Tidak ada transaksi untuk periode ini.</p>
            </div>
        @endif
    </div>

    <!-- Export Button -->
    <div class="export-section">
        <button onclick="window.print()" class="btn-export">
            <i class="fas fa-file-pdf"></i> Export ke PDF
        </button>
    </div>
</div>

<style>
* {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
}

.riwayat-full {
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

.saldo-card {
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    color: white;
}

.saldo-icon {
    width: 56px;
    height: 56px;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.saldo-info {
    flex: 1;
}

.saldo-label {
    font-size: 13px;
    opacity: 0.9;
    display: block;
}

.saldo-value {
    font-size: 28px;
    font-weight: 700;
    margin: 4px 0 0;
    color: white;
}

.filter-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #555;
    display: flex;
    align-items: center;
    gap: 6px;
}

.filter-select, .filter-input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 14px;
    background: white;
}

.btn-filter {
    background: #10b981;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.stats-kas {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.kas-card {
    background: white;
    border-radius: 16px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.kas-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.kas-masuk .kas-icon {
    background: #dcfce7;
    color: #10b981;
}

.kas-keluar .kas-icon {
    background: #fee2e2;
    color: #ef4444;
}

.kas-info {
    flex: 1;
}

.kas-label {
    font-size: 12px;
    color: #666;
}

.kas-value {
    font-size: 20px;
    margin: 4px 0 0;
}

.transaksi-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    margin-bottom: 24px;
}

.card-header {
    padding: 16px 20px;
    background: #fafbfc;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
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
}

.transaksi-list {
    padding: 8px 0;
}

.transaksi-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
}

.transaksi-item:hover {
    background: #fafafa;
}

.transaksi-left {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    flex: 1;
}

.transaksi-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.transaksi-icon.income {
    background: #dcfce7;
    color: #10b981;
}

.transaksi-icon.outcome {
    background: #fee2e2;
    color: #ef4444;
}

.transaksi-detail {
    flex: 1;
}

.transaksi-kategori {
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.badge-auto, .badge-manual {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: normal;
}

.badge-auto {
    background: #e0f2fe;
    color: #0284c7;
}

.badge-manual {
    background: #f1f5f9;
    color: #475569;
}

.transaksi-desc {
    font-size: 13px;
    color: #555;
    margin-bottom: 4px;
}

.transaksi-date {
    font-size: 11px;
    color: #999;
    display: flex;
    align-items: center;
    gap: 4px;
}

.btn-delete {
    background: none;
    border: 1px solid #fee2e2;
    color: #ef4444;
    padding: 8px 16px;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    transition: all 0.2s;
}

.btn-delete:hover {
    background: #fee2e2;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 56px;
    color: #ccc;
    margin-bottom: 16px;
}

.empty-state p {
    color: #999;
}

.export-section {
    text-align: right;
}

.btn-export {
    background: #1e293b;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.btn-export:hover {
    background: #0f172a;
}

.text-green {
    color: #10b981;
    font-weight: 600;
}

.text-red {
    color: #ef4444;
    font-weight: 600;
}

@media (max-width: 768px) {
    .riwayat-full {
        padding: 16px;
    }
    .stats-kas {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    .transaksi-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    .transaksi-right {
        width: 100%;
    }
    .btn-delete {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection