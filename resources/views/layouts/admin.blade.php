<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Sistem Absensi</title>
    <style>
        /* Styling Umum */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6; /* Latar belakang body terang */
            color: #333;
        }

        /* 1. HEADER (Navigation Bar) */
        header {
            background-color: #34495e; /* Biru tua/Dark Navy */
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000; /* Pastikan header selalu di atas */
        }

        header h1 {
            margin: 0;
            font-size: 1.6em;
            font-weight: 700;
        }

        header h1 a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        header h1 a:hover {
            color: #ecf0f1;
        }

        /* Navigasi Utama */
        nav {
            display: flex;
            align-items: center;
            font-size: 0.95em;
        }

        /* Link Navigasi Dasar */
        nav > a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        nav > a:hover {
            background-color: #2c3e50;
        }

        /* Info Admin & Logout */
        nav > span {
            margin-left: 20px;
            padding-left: 15px;
            border-left: 1px solid #7f8c8d;
            color: #bdc3c7;
            font-weight: 500;
        }

        nav form button {
            background-color: #e74c3c !important; /* Merah untuk Logout */
            color: white !important;
            padding: 8px 15px !important;
            border: none !important;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        nav form button:hover {
            background-color: #c0392b !important;
        }


        /* 2. DROPDOWN (Data Master & Laporan) */
        .dropdown {
            position: relative;
            display: inline-block;
            margin-right: 10px;
        }

        .dropbtn {
            padding: 8px 15px;
            cursor: pointer;
            display: block;
            color: white;
            text-decoration: none;
        }

        .dropdown:hover .dropbtn {
            background-color: #2c3e50;
            border-radius: 4px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #4a5a6a;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.3);
            z-index: 100;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
            /* Animasi */
            opacity: 0;
            visibility: hidden;
            transform: translateY(5px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
        }

        .dropdown:hover .dropdown-content {
            display: block;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-content a {
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            font-size: 0.95em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown-content a:hover {
            background-color: #34495e;
        }


        /* 3. MAIN CONTENT & ALERTS */
        .main-content {
            padding: 30px;
            max-width: 1400px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* Notifikasi (Alerts) */
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 6px;
            font-weight: 500;
            opacity: 0.95;
        }

        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }


        /* 4. STYLING UNTUK SEMUA TABEL DATA MASTER (Index Pages) */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 0.95em;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 6px;
            overflow: hidden;
        }

        table th, table td {
            padding: 14px 18px;
            border: 1px solid #e9ecef;
            text-align: left;
        }

        table thead tr {
            background-color: #34495e;
            color: white;
            font-weight: 600;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tbody tr:hover {
            background-color: #e9eff5;
        }

        /* 5. STYLING UNTUK TOMBOL AKSI CRUD */

        /* Tombol Dasar (di dalam sel tabel atau di atas tabel) */
        .main-content a, .main-content button, .main-content form button {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: inline-block;
            margin-right: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .main-content a:hover, .main-content button:hover:not(:disabled) {
            opacity: 0.9;
        }

        /* Tombol + Tambah Baru (Hijau) */
        a[style*="background-color: green"] {
            background-color: #28a745 !important;
            color: white !important;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(40, 167, 69, 0.3);
        }

        /* Tombol Edit (Orange) */
        a[style*="background-color: orange"] {
            background-color: #ffc107 !important;
            color: #333 !important;
        }

        /* Tombol Hapus (Merah) */
        button[type="submit"][style*="background-color: red"] {
            background-color: #dc3545 !important;
            color: white !important;
        }

        /* Tombol Simpan/Submit Form (Biru) */
        button[type="submit"][style*="background-color: blue"] {
            background-color: #007bff !important;
            color: white !important;
        }


        /* 6. STYLING UNTUK FORM INPUT (Create/Edit Pages - Asumsi menggunakan class .data-master-form) */

        /* Jika menggunakan class .data-master-form */
        .data-master-form > div {
            margin-bottom: 20px;
        }

        .data-master-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }

        .data-master-form input,
        .data-master-form select,
        .data-master-form textarea {
            width: 100%;
            max-width: 550px;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .data-master-form textarea {
            min-height: 120px;
        }

        .data-master-form input:focus, .data-master-form select:focus, .data-master-form textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
            outline: none;
        }

        .data-master-form button[type="submit"] {
            padding: 12px 25px;
            margin-top: 10px;
        }

        /* Styling pesan error (global fallback) */
        p[style*="color: red; margin: 0;"] {
            color: #e3342f !important;
            font-size: 0.9em;
            margin-top: 5px !important;
        }
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

            {{-- <div class="dropdown">
                <a href="#" class="dropbtn">Laporan ▼</a>
                <div class="dropdown-content">
                    <a href="{{ route('admin.laporan.rekap.form') }}">Rekap Absensi</a>
                    <a href="#">Laporan Khusus</a>
                </div>
            </div> --}}

            <span style="font-weight: bold;">
                Admin: {{ Auth::guard('admin')->user()->nama }}
            </span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline; margin-left: 10px;">
                @csrf
                <button type="submit">Logout</button>
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
