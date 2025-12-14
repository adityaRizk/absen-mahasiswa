<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Dosen') | Sistem Absensi</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #ecf0f1; }
        header { background-color: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: white; text-decoration: none; margin: 0 10px; }
        header h1 { margin: 0; font-size: 1.5em; }
        nav { display: flex; align-items: center; }
        nav > a:hover, nav > span a:hover { text-decoration: underline; }
        nav > span { margin-left: 20px; padding-left: 15px; border-left: 1px solid #7f8c8d; }
        .main-content { padding: 30px; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid transparent; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .alert-warning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }
    </style>
</head>
<body>
    <header>
        <h1><a href="{{ route('dosen.dashboard') }}">Dosen Panel</a></h1>
        <nav>
            <a href="{{ route('dosen.dashboard') }}">Dashboard (Semua Jadwal)</a> |
            <a href="{{ route('dosen.profil') }}">Profil</a>

            <span>
                Selamat Datang, {{ Auth::guard('dosen')->user()->nama }}
            </span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline; margin-left: 10px;">
                @csrf
                <button type="submit" style="background-color: #e74c3c; color: white; padding: 5px 10px; border: none; cursor: pointer;">Logout</button>
            </form>
        </nav>
    </header>

    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
