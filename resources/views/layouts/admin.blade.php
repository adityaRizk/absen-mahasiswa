<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Sistem Absensi</title>
    <style>
        /* Gaya dasar agar struktur terlihat jelas */
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        header { background-color: #34495e; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: white; text-decoration: none; margin: 0 5px; }
        header h1 { margin: 0; font-size: 1.5em; }
        nav { display: flex; align-items: center; }
        nav > span { margin-left: 20px; padding-left: 15px; border-left: 1px solid #7f8c8d; }
        .dropdown { position: relative; display: inline-block; margin-right: 15px; }
        .dropdown-content { display: none; position: absolute; background-color: #5d6d7e; min-width: 160px; z-index: 1; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); }
        .dropdown-content a { color: white; padding: 8px 16px; text-decoration: none; display: block; }
        .dropdown:hover .dropdown-content { display: block; }
        .dropdown-content a:hover { background-color: #4a5a6a; }
        .main-content { padding: 20px; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    </style>
</head>
<body>
    <header>
        <h1><a href="{{ route('admin.dashboard') }}">Admin Panel</a></h1>
        <nav>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>

            <div class="dropdown">
                <a href="#" class="dropbtn">Data Master ▼</a>
                <div class="dropdown-content">
                    <a href="{{ route('admin.datamaster.dosen.index') }}">Data Dosen</a>
                    <a href="{{ route('admin.datamaster.mahasiswa.index') }}">Data Mahasiswa</a>
                    <a href="{{ route('admin.datamaster.kelas.index') }}">Data Kelas</a>
                    <a href="{{ route('admin.datamaster.matkul.index') }}">Data Matkul</a>
                    <a href="{{ route('admin.datamaster.jadwal.index') }}">Data Jadwal</a>
                </div>
            </div>

            <div class="dropdown">
                <a href="#" class="dropbtn">Laporan ▼</a>
                <div class="dropdown-content">
                    <a href="{{ route('admin.laporan.rekap.form') }}">Rekap Absensi</a>
                    <a href="#">Laporan Khusus</a>
                </div>
            </div>

            <span style="font-weight: bold;">
                Admin: {{ Auth::guard('admin')->user()->nama }}
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
