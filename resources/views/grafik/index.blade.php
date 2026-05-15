@extends('layouts.app')

@section('title', 'Visualisasi Grafik')

@section('content')
    <h2>Visualisasi Grafik</h2>

    <!-- Filter -->
    <div>
        <form method="GET" action="{{ route('grafik') }}">
            <div>
                <select name="view_type">
                    <option value="monthly" {{ $viewType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $viewType == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
                
                @if($viewType == 'monthly')
                    <input type="text" name="month" placeholder="MM" value="{{ $month }}" size="2">
                    <input type="text" name="year" placeholder="YYYY" value="{{ $year }}" size="4">
                @else
                    <input type="text" name="year" placeholder="YYYY" value="{{ $year }}" size="4">
                @endif
                
                <button type="submit">Tampilkan</button>
            </div>
        </form>
    </div>

    <hr>

    <!-- Persentase Penghematan -->
    <div>
        <h3>Anda menghemat <strong>{{ $hematPersen }}%</strong> pengeluaran</h3>
        <p>({{ 100 - $hematPersen }}% dari pendapatan digunakan)</p>
    </div>

    <hr>

    <!-- Diagram Bulat (plain HTML/text version) -->
    <h3>Diagram Kategori {{ $viewType == 'monthly' ? "Bulan $month/$year" : "Tahun $year" }}</h3>
    
    @if($data->count() > 0)
        <div>
            <h4>Pemasukan (Hijau)</h4>
            @php
                $totalIncome = $data->where('type', 'income')->sum('total');
            @endphp
            @foreach($data->where('type', 'income') as $item)
                <div>
                    <strong>{{ $item->category->name ?? '?' }}</strong> - 
                    Rp {{ number_format($item->total, 0, ',', '.') }} 
                    ({{ $totalIncome > 0 ? round(($item->total / $totalIncome) * 100, 2) : 0 }}%)
                    <span style="color: green;">🟢</span>
                </div>
            @endforeach
        </div>
        
        <div style="margin-top: 20px;">
            <h4>Pengeluaran (Merah)</h4>
            @php
                $totalOutcome = $data->where('type', 'outcome')->sum('total');
            @endphp
            @foreach($data->where('type', 'outcome') as $item)
                <div>
                    <strong>{{ $item->category->name ?? '?' }}</strong> - 
                    Rp {{ number_format($item->total, 0, ',', '.') }} 
                    ({{ $totalOutcome > 0 ? round(($item->total / $totalOutcome) * 100, 2) : 0 }}%)
                    <span style="color: red;">🔴</span>
                </div>
            @endforeach
        </div>
        
        <hr>
        <div>
            <p><strong>Total Pemasukan:</strong> <span style="color: green;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span></p>
            <p><strong>Total Pengeluaran:</strong> <span style="color: red;">Rp {{ number_format($totalOutcome, 0, ',', '.') }}</span></p>
        </div>
    @else
        <p>Tidak ada data transaksi untuk periode ini.</p>
    @endif
@endsection