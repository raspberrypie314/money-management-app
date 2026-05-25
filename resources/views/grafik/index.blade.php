@extends('layouts.app')

@section('title', 'Visualisasi Grafik')

@section('content')
<div class="grafik-full">
    <div class="page-header">
        <h1><i class="fas fa-chart-pie"></i> Visualisasi Grafik</h1>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('grafik') }}" class="filter-form">
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
                    <i class="fas fa-chart-line"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Penghematan Card -->
    <div class="hemat-card">
        <div class="hemat-icon">
            <i class="fas fa-piggy-bank"></i>
        </div>
        <div class="hemat-info">
            <span class="hemat-label">Anda menghemat</span>
            <h2 class="hemat-value">{{ $hematPersen }}%</h2>
            <span class="hemat-desc">pengeluaran ({{ 100 - $hematPersen }}% dari pendapatan digunakan)</span>
        </div>
    </div>

    <!-- Diagram Section -->
    <div class="chart-card">
        <div class="card-header">
            <h3><i class="fas fa-chart-simple"></i> Diagram Kategori {{ $viewType == 'monthly' ? "Bulan $month/$year" : "Tahun $year" }}</h3>
        </div>

        @if($data->count() > 0)
            @php
                $totalIncome = $data->where('type', 'income')->sum('total');
                $totalOutcome = $data->where('type', 'outcome')->sum('total');
            @endphp

            <div class="chart-grid">
                <!-- Pemasukan Section -->
                <div class="chart-section income-section">
                    <div class="section-title">
                        <i class="fas fa-arrow-down text-green"></i>
                        <span>Pemasukan</span>
                        <span class="section-total text-green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                    </div>
                    <div class="chart-list">
                        @forelse($data->where('type', 'income') as $item)
                        <div class="chart-item">
                            <div class="chart-item-left">
                                <div class="chart-item-icon green-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="chart-item-info">
                                    <div class="chart-item-name">{{ $item->category->name ?? 'Kategori tidak ditemukan' }}</div>
                                    <div class="chart-item-amount">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="chart-item-right">
                                <div class="chart-percent">
                                    {{ $totalIncome > 0 ? round(($item->total / $totalIncome) * 100, 2) : 0 }}%
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill green-fill" style="width: {{ $totalIncome > 0 ? ($item->total / $totalIncome) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-small">
                            <i class="fas fa-info-circle"></i> Tidak ada data pemasukan
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pengeluaran Section -->
                <div class="chart-section outcome-section">
                    <div class="section-title">
                        <i class="fas fa-arrow-up text-red"></i>
                        <span>Pengeluaran</span>
                        <span class="section-total text-red">Rp {{ number_format($totalOutcome, 0, ',', '.') }}</span>
                    </div>
                    <div class="chart-list">
                        @forelse($data->where('type', 'outcome') as $item)
                        <div class="chart-item">
                            <div class="chart-item-left">
                                <div class="chart-item-icon red-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="chart-item-info">
                                    <div class="chart-item-name">{{ $item->category->name ?? 'Kategori tidak ditemukan' }}</div>
                                    <div class="chart-item-amount">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="chart-item-right">
                                <div class="chart-percent">
                                    {{ $totalOutcome > 0 ? round(($item->total / $totalOutcome) * 100, 2) : 0 }}%
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill red-fill" style="width: {{ $totalOutcome > 0 ? ($item->total / $totalOutcome) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-small">
                            <i class="fas fa-info-circle"></i> Tidak ada data pengeluaran
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="summary">
                <div class="summary-item">
                    <i class="fas fa-chart-line text-green"></i>
                    <span>Total Pemasukan:</span>
                    <strong class="text-green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
                </div>
                <div class="summary-item">
                    <i class="fas fa-chart-line text-red"></i>
                    <span>Total Pengeluaran:</span>
                    <strong class="text-red">Rp {{ number_format($totalOutcome, 0, ',', '.') }}</strong>
                </div>
                <div class="summary-item">
                    <i class="fas fa-wallet"></i>
                    <span>Selisih:</span>
                    <strong class="{{ ($totalIncome - $totalOutcome) >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($totalIncome - $totalOutcome, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-chart-line"></i>
                <p>Tidak ada data transaksi untuk periode ini.</p>
                <a href="{{ route('budgeting') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
            </div>
        @endif
    </div>
</div>

<style>
* {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
}

.grafik-full {
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

.hemat-card {
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    color: white;
}

.hemat-icon {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
}

.hemat-info {
    flex: 1;
}

.hemat-label {
    font-size: 14px;
    opacity: 0.9;
    display: block;
}

.hemat-value {
    font-size: 48px;
    font-weight: 700;
    margin: 4px 0;
}

.hemat-desc {
    font-size: 13px;
    opacity: 0.8;
}

.chart-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.card-header {
    padding: 16px 20px;
    background: #fafbfc;
    border-bottom: 1px solid #eee;
}

.card-header h3 {
    font-size: 16px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
}

@media (max-width: 900px) {
    .chart-grid {
        grid-template-columns: 1fr;
    }
}

.chart-section {
    padding: 20px;
}

.income-section {
    border-right: 1px solid #eee;
}

.outcome-section {
    border-left: 1px solid #eee;
}

@media (max-width: 900px) {
    .income-section {
        border-right: none;
        border-bottom: 1px solid #eee;
    }
    .outcome-section {
        border-left: none;
    }
}

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 16px;
    margin-bottom: 16px;
    border-bottom: 1px solid #eee;
    font-weight: 600;
    font-size: 16px;
}

.section-total {
    margin-left: auto;
    font-size: 14px;
}

.chart-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.chart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.chart-item-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 150px;
}

.chart-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.green-icon {
    background: #dcfce7;
    color: #10b981;
}

.red-icon {
    background: #fee2e2;
    color: #ef4444;
}

.chart-item-info {
    flex: 1;
}

.chart-item-name {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.chart-item-amount {
    font-size: 12px;
    color: #666;
}

.chart-item-right {
    min-width: 120px;
}

.chart-percent {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    text-align: right;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 10px;
}

.green-fill {
    background: #10b981;
}

.red-fill {
    background: #ef4444;
}

.summary {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 16px 20px;
    background: #fafbfc;
    border-top: 1px solid #eee;
    flex-wrap: wrap;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
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
    margin-bottom: 20px;
}

.empty-small {
    text-align: center;
    padding: 20px;
    color: #999;
    font-size: 13px;
}

.btn-add {
    display: inline-block;
    background: #10b981;
    color: white;
    padding: 10px 24px;
    border-radius: 30px;
    text-decoration: none;
    font-size: 14px;
}

.text-green {
    color: #10b981;
}

.text-red {
    color: #ef4444;
}

@media (max-width: 768px) {
    .grafik-full {
        padding: 16px;
    }
    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    .hemat-card {
        flex-direction: column;
        text-align: center;
    }
    .hemat-value {
        font-size: 36px;
    }
    .summary {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection