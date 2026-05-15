@extends('layouts.app')

@section('title', 'Budgeting - ' . ucfirst($mode))

@section('content')
    <h2>Budgeting - Manajemen {{ $mode == 'outcome' ? 'Pengeluaran' : 'Pemasukan' }}</h2>

    <!-- Tombol Mode -->
    <div>
        <a href="{{ route('budgeting', ['mode' => 'outcome', 'sort' => $sort]) }}" style="padding: 5px; background: {{ $mode == 'outcome' ? '#ccc' : '#fff' }};">Pengeluaran</a>
        <a href="{{ route('budgeting', ['mode' => 'income', 'sort' => $sort]) }}" style="padding: 5px; background: {{ $mode == 'income' ? '#ccc' : '#fff' }};">Pemasukan</a>
    </div>

    <hr>

    <!-- Tombol Terapkan ke Bulan Ini (🔥 TAMBAHKAN DI SINI) -->
    <form method="POST" action="{{ route('budgeting.apply') }}" style="margin-bottom: 20px;">
        @csrf
        <button type="submit" style="background: green; color: white; padding: 10px; border: none; cursor: pointer;">
            💰 Terapkan ke Bulan Ini
        </button>
    </form>

    <!-- Tombol Sort -->
    <div>
        <strong>Urutkan:</strong>
        <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => 'nama_asc']) }}">Nama ↑</a>
        <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => 'nama_desc']) }}">Nama ↓</a>
        <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => 'nilai_asc']) }}">Nilai ↑</a>
        <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => 'nilai_desc']) }}">Nilai ↓</a>
    </div>

    <hr>

    <!-- Form Create -->
    <h3>Tambah {{ $mode == 'outcome' ? 'Pengeluaran' : 'Pemasukan' }} Baru</h3>
    <form method="POST" action="{{ route('budgeting') }}">
        @csrf
        <div>
            <label>Nama Kategori:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Tipe:</label>
            <select name="type" required>
                <option value="manual">Manual (sekali pakai)</option>
                <option value="auto">Otomatis (berulang)</option>
            </select>
        </div>
        <div>
            <label>Nilai:</label>
            <input type="number" name="value" step="1000" required>
        </div>
        <div>
            <label>Jangka (untuk otomatis):</label>
            <select name="period">
                <option value="">-- Tidak ada (manual) --</option>
                <option value="weekly">Per Minggu</option>
                <option value="monthly">Per Bulan</option>
                <option value="yearly">Per Tahun</option>
            </select>
        </div>
        <div>
            <label>Ikon (URL gambar):</label>
            <input type="url" name="icon" placeholder="https://...">
        </div>
        @if($mode == 'income')
        <div>
            <label>
                <input type="checkbox" name="is_primary_income" value="1">
                Kas Utama (tidak dianggap tambahan kas masuk)
            </label>
        </div>
        @endif
        <input type="hidden" name="category_type" value="{{ $mode }}">
        <div>
            <button type="submit">Simpan</button>
            <button type="reset">Reset</button>
        </div>
    </form>

    <hr>

    <!-- Tabel Data -->
    <h3>Daftar {{ $mode == 'outcome' ? 'Pengeluaran' : 'Pemasukan' }}</h3>
    @if($kategori->count() > 0)
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Ikon</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Nilai</th>
                    <th>Jangka</th>
                    <th>Total/Bulan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->icon ? '🖼️' : '📁' }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->type == 'auto' ? 'Otomatis' : 'Manual' }}</td>
                    <td>Rp {{ number_format($item->value, 0, ',', '.') }}</td>
                    <td>{{ $item->period ?? '-' }}</td>
                    <td>Rp {{ number_format($item->monthly_value, 0, ',', '.') }}</td>
                    <td>
                        <!-- Edit Button (mengarah ke form edit di bawah) -->
                        <a href="?mode={{ $mode }}&sort={{ $sort }}&edit={{ $item->id }}">Edit</a> |
                        <form method="POST" action="{{ route('budgeting.destroy', $item->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus kategori ini?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Belum ada data. Silakan tambah di atas.</p>
    @endif

    <hr>

    <!-- Form Edit (muncul jika ada parameter edit) -->
    @if(request('edit'))
        @php
            $editItem = $kategori->where('id', request('edit'))->first();
        @endphp
        @if($editItem)
        <h3>Edit {{ $editItem->name }}</h3>
        <form method="POST" action="{{ route('budgeting.update', $editItem->id) }}">
            @csrf
            @method('PUT')
            <div>
                <label>Nama Kategori:</label>
                <input type="text" name="name" value="{{ $editItem->name }}" required>
            </div>
            <div>
                <label>Tipe:</label>
                <select name="type" required>
                    <option value="manual" {{ $editItem->type == 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="auto" {{ $editItem->type == 'auto' ? 'selected' : '' }}>Otomatis</option>
                </select>
            </div>
            <div>
                <label>Nilai:</label>
                <input type="number" name="value" step="1000" value="{{ $editItem->value }}" required>
            </div>
            <div>
                <label>Jangka:</label>
                <select name="period">
                    <option value="">-- Tidak ada --</option>
                    <option value="weekly" {{ $editItem->period == 'weekly' ? 'selected' : '' }}>Per Minggu</option>
                    <option value="monthly" {{ $editItem->period == 'monthly' ? 'selected' : '' }}>Per Bulan</option>
                    <option value="yearly" {{ $editItem->period == 'yearly' ? 'selected' : '' }}>Per Tahun</option>
                </select>
            </div>
            <div>
                <label>Ikon (URL):</label>
                <input type="url" name="icon" value="{{ $editItem->icon }}">
            </div>
            <div>
                <button type="submit">Simpan Perubahan</button>
                <a href="{{ route('budgeting', ['mode' => $mode, 'sort' => $sort]) }}">Cancel</a>
            </div>
        </form>
        @endif
    @endif
@endsection