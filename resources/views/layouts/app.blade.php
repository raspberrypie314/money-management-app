<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Keuangan App')</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f5f7fa;
            color: #1a1a2e;
        }
        
        /* ========== DESKTOP NAVBAR ========== */
        .navbar {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .navbar-menu {
            display: flex;
            gap: 24px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .navbar-menu a {
            text-decoration: none;
            color: #555;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }
        
        .navbar-menu a:hover {
            color: #10b981;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .search-form {
            display: flex;
            align-items: center;
            background: #f0f0f0;
            border-radius: 20px;
            padding: 6px 12px;
        }
        
        .search-form input {
            border: none;
            background: none;
            padding: 6px 8px;
            font-size: 13px;
            outline: none;
            width: 180px;
        }
        
        .search-form button {
            background: none;
            border: none;
            cursor: pointer;
            color: #777;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-name {
            font-size: 13px;
            color: #333;
        }
        
        .logout-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        /* ========== MOBILE HEADER (HAMBURGER) ========== */
        .mobile-header {
            display: none;
            background: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 16px;
            position: sticky;
            top: 0;
            z-index: 100;
            align-items: center;
            justify-content: space-between;
        }
        
        .mobile-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #10b981;
        }
        
        .hamburger-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
            padding: 8px;
        }
        
        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background: white;
            box-shadow: 2px 0 12px rgba(0,0,0,0.1);
            transition: left 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.open {
            left: 0;
        }
        
        .sidebar-header {
            padding: 20px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .sidebar-header h3 {
            font-size: 18px;
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .close-sidebar {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #999;
        }
        
        .sidebar-menu {
            flex: 1;
            padding: 16px 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            text-decoration: none;
            color: #444;
            font-size: 15px;
            transition: all 0.2s;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #f0fdf4;
            color: #10b981;
        }
        
        .sidebar-menu hr {
            margin: 12px 20px;
            border: none;
            border-top: 1px solid #eee;
        }
        
        .sidebar-logout {
            width: 100%;
            text-align: left;
            padding: 14px 20px;
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .sidebar-overlay.open {
            display: block;
        }
        
        /* Mobile Search Bar */
        .mobile-search {
            display: none;
            background: white;
            padding: 8px 16px;
            border-bottom: 1px solid #eee;
        }
        
        .mobile-search .search-form {
            width: 100%;
            background: #f5f5f5;
        }
        
        .mobile-search .search-form input {
            flex: 1;
            width: 100%;
        }
        
        /* Alerts */
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }
        
        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .navbar {
                display: none;
            }
            
            .mobile-header {
                display: flex;
            }
            
            .mobile-search {
                display: block;
            }
            
            .main-content {
                padding: 16px;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-header, .mobile-search, .sidebar, .sidebar-overlay {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    @if(session('user_id'))
    <!-- DESKTOP NAVBAR -->
    <div class="navbar">
        <div class="navbar-container">
            <div class="navbar-menu">
                <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="{{ route('budgeting') }}"><i class="fas fa-chart-line"></i> Budgeting</a>
                <a href="{{ route('riwayat') }}"><i class="fas fa-history"></i> Riwayat</a>
                <a href="{{ route('grafik') }}"><i class="fas fa-chart-pie"></i> Grafik</a>
                <a href="{{ route('profil') }}"><i class="fas fa-user"></i> Profil</a>
            </div>
            <div class="navbar-right">
                <form class="search-form" method="GET" action="{{ url()->current() }}">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}">
                    <button type="submit"><i class="fas fa-arrow-right"></i></button>
                </form>
                <div class="user-info">
                    <span class="user-name"><i class="fas fa-user-circle"></i> {{ session('user_name') }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MOBILE HEADER -->
    <div class="mobile-header">
        <div class="mobile-logo">
            <i class="fas fa-chart-line"></i>
            <span>Finance App</span>
        </div>
        <button class="hamburger-btn" id="hamburgerBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- MOBILE SEARCH -->
    <div class="mobile-search">
        <form class="search-form" method="GET" action="{{ url()->current() }}" style="width: 100%;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" style="flex: 1;">
            <button type="submit"><i class="fas fa-arrow-right"></i></button>
        </form>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-chart-line"></i> Menu</h3>
            <button class="close-sidebar" id="closeSidebarBtn"><i class="fas fa-times"></i></button>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="{{ route('budgeting') }}"><i class="fas fa-chart-line"></i> Budgeting</a>
            <a href="{{ route('riwayat') }}"><i class="fas fa-history"></i> Riwayat</a>
            <a href="{{ route('grafik') }}"><i class="fas fa-chart-pie"></i> Grafik</a>
            <a href="{{ route('profil') }}"><i class="fas fa-user"></i> Profil</a>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @endif

    <div class="main-content">
        @if(session('success'))
            <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
        @endif
        
        @yield('content')
    </div>

    <script>
        (function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');

            if (!sidebar || !overlay) return;

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            }

            if (hamburgerBtn) {
                hamburgerBtn.addEventListener('click', openSidebar);
            }
            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', closeSidebar);
            }
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>