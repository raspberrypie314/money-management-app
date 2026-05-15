@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <h2>Riwayat Transaksi</h2>

    <!-- Total Saldo -->
    <div>
        <p>Total Saldo Tersedia: <strong>Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</strong></p>
    </div>

    <!-- Filter -->
    <div>
        <form method="GET" action="{{ route('riwayat') }}">
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
                
                <button type="submit">Filter</button>
            </div>
        </form>
    </div>

    <hr>

    <!-- Total Kas Masuk/Keluar -->
    <div>
        <p>Total Kas Masuk: <span style="color: green;">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span></p>
        <p>Total Kas Keluar: <span style="color: red;">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span></p>
    </div>

    <hr>

    <!-- Daftar Transaksi -->
    <h3>Daftar Transaksi</h3>
    @if($transactions->count() > 0)
        @foreach($transactions as $item)
        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
            <p>
                @if($item->type == 'income')
                    <span style="color: green;">(Saldo +Rp {{ number_format($item->amount, 0, ',', '.') }}) telah ditambahkan pada arus kas masuk sebagai {{ $item->category->name ?? '?' }}</span>
                @else
                    <span style="color: red;">(Saldo -Rp {{ number_format($item->amount, 0, ',', '.') }}) telah ditambahkan pada arus kas keluar sebagai {{ $item->category->name ?? '?' }}</span>
                @endif
                
                @if($item->category && $item->category->type == 'manual')
                    <br><small>Anda telah menambahkan secara manual</small>
                @elseif($item->category && $item->category->type == 'auto')
                    <br><small>Transaksi otomatis bulanan</small>
                @endif
            </p>
            <p><small>Tanggal: {{ $item->transaction_date }}</small></p>
            
            <!-- Tombol Hapus Data -->
            <form method="POST" action="{{ route('riwayat.destroy', $item->id) }}" onsubmit="return confirm('Hapus transaksi ini? Data tidak bisa dikembalikan.')">
                @csrf
                @method('DELETE')
                <button type="submit" style="color: red;">Hapus Data</button>
            </form>
        </div>
        @endforeach
    @else
        <p>Tidak ada transaksi untuk periode ini.</p>
    @endif

    <hr>
    <div>
        <button onclick="window.print()">Export ke PDF (Print)</button>
    </div>
@endsection