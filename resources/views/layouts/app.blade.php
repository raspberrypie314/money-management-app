<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Finance App')</title>
</head>
<body>

    @if(session('user_id'))
    <!-- Navbar -->
    <div style="border-bottom: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <a href="{{ route('dashboard') }}">🏠 Dashboard</a> |
                <a href="{{ route('budgeting') }}">📊 Budgeting</a> |
                <a href="{{ route('riwayat') }}">📜 Riwayat</a> |
                <a href="{{ route('grafik') }}">📈 Grafik</a> |
                <a href="{{ route('profil') }}">👤 Profil</a>
            </div>
            <div>
                Hallo, {{ session('user_name') }} |
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
        <hr>
        <!-- Pencarian -->
        <form method="GET" action="{{ url()->current() }}" style="margin-top: 10px;">
            <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}">
            <button type="submit">Cari</button>
        </form>
    </div>
    @endif

    <!-- Pesan sukses/error -->
    @if(session('success'))
        <div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">{{ session('error') }}</div>
    @endif

    <!-- Konten Utama -->
    <div>
        @yield('content')
    </div>

</body>
</html>