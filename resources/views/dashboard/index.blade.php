@extends('layouts.app')

@section('title', 'Dashboard - ' . ucfirst($mode))

@section('content')
    <h2>Hallo, {{ session('user_name') }}</h2>

    <!-- Saldo -->
    <div>
        <p>Saldo Efektif Anda: 
            <strong style="color: green;">Rp {{ number_format($saldoEfektif, 0, ',', '.') }}</strong>
        </p>
        <p>Total Pemasukan: <span style="color: green;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span></p>
        <p>Total Pengeluaran: <span style="color: red;">Rp {{ number_format($totalOutcome, 0, ',', '.') }}</span></p>
    </div>

    <hr>

    <!-- Tombol Mode dan Sort -->
    <div>
        <div>
            <strong>Mode:</strong>
            <a href="{{ route('dashboard', ['mode' => 'outcome', 'sort' => $sort]) }}" style="padding: 5px; background: {{ $mode == 'outcome' ? '#ccc' : '#fff' }};">Pengeluaran</a>
            <a href="{{ route('dashboard', ['mode' => 'income', 'sort' => $sort]) }}" style="padding: 5px; background: {{ $mode == 'income' ? '#ccc' : '#fff' }};">Pemasukan</a>
        </div>
        <div>
            <strong>Urutkan:</strong>
            <a href="{{ route('dashboard', ['mode' => $mode, 'sort' => 'nama_asc']) }}">Nama ↑</a>
            <a href="{{ route('dashboard', ['mode' => $mode, 'sort' => 'nama_desc']) }}">Nama ↓</a>
            <a href="{{ route('dashboard', ['mode' => $mode, 'sort' => 'nilai_asc']) }}">Nilai ↑</a>
            <a href="{{ route('dashboard', ['mode' => $mode, 'sort' => 'nilai_desc']) }}">Nilai ↓</a>
        </div>
    </div>

    <hr>

    <!-- Daftar Kategori -->
    <h3>{{ $mode == 'outcome' ? 'Pengeluaran' : 'Pemasukan Non Kas Utama' }}</h3>
    
    @if($kategori->count() > 0)
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Ikon</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $item)
                <tr>
                    <td>{{ $item->icon ? '🖼️' : '📁' }}</td>
                    <td>{{ $item->name }}</td>
                    <td style="{{ $mode == 'outcome' ? 'color: red;' : 'color: green;' }}">
                        {{ $mode == 'outcome' ? '-' : '+' }} Rp {{ number_format($item->value, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Belum ada data {{ $mode == 'outcome' ? 'pengeluaran' : 'pemasukan' }}.</p>
        <p>Silakan tambahkan di menu <a href="{{ route('budgeting', ['mode' => $mode]) }}">Budgeting</a>.</p>
    @endif
@endsection