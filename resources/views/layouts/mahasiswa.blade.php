<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Mahasiswa')</title>
    <style>
        /* Styling Umum */
        body {
            font-family: 'Poppins', sans-serif; /* Menggunakan font yang sedikit berbeda */
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; /* Latar belakang body Biru Langit Muda */
            color: #333;
        }

        /* 1. HEADER (Navigation Bar) */
        header {
            background-color: #007bff; /* Biru Primer */
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
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
            color: #cce5ff;
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
            font-weight: 500;
        }

        nav > a:hover {
            background-color: #0056b3;
        }

        /* Garis pemisah antara link dan info user */
        nav > span {
            margin-left: 20px;
            padding-left: 15px;
            border-left: 1px solid #7f8c8d;
            color: #cce5ff; /* Warna biru muda terang */
            font-weight: 500;
        }

        /* Tombol Logout */
        nav form button {
            background-color: #dc3545; /* Merah untuk Logout */
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        nav form button:hover {
            background-color: #c82333;
        }


        /* 2. MAIN CONTENT & ALERTS */
        .main-content {
            padding: 30px;
            max-width: 1000px;
            margin: 25px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #007bff;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* Notifikasi (Alerts) */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: 500;
            opacity: 0.95;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }


        /* 3. STYLING TABEL DAN TOMBOL KHUSUS MAHASISWA */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #e9ecef;
            text-align: left;
        }

        table thead tr {
            background-color: #007bff;
            color: white;
        }

        table tbody tr:nth-child(even) {
            background-color: #f7f9fc;
        }

        table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Styling untuk highlight baris hari ini (biru muda) */
        tr[style*="background-color: #e6f7ff"] {
            background-color: #cce5ff !important;
            border-left: 5px solid #007bff;
        }

        /* Link Aksi (Lihat Riwayat Absensi) */
        a[style*="background-color: teal"] {
            background-color: #17a2b8 !important; /* Teal/Cyan */
            color: white !important;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        a[style*="background-color: teal"]:hover {
            opacity: 0.8;
        }

        /* Tombol ABSEN SEKARANG */
        button[style*="background-color: #007bff"] {
            background-color: #28a745 !important; /* Mengubah tombol absen utama menjadi hijau */
            color: white !important;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[style*="background-color: #007bff"]:hover:not(:disabled) {
            background-color: #1e7e34 !important;
        }

        /* Tombol Disabled */
        button:disabled {
            background-color: #6c757d !important;
            color: white !important;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Status Absensi di Riwayat (Detail Matkul) */
        span[style*="background-color: green"] { background-color: #28a745 !important; }
        span[style*="background-color: orange"] { background-color: #ffc107 !important; color: #333 !important; }
        span[style*="background-color: red"] { background-color: #dc3545 !important; }
    </style>
</head>
<body>
    <header>
        <h1>Sistem Absensi Mahasiswa</h1>
        <nav>
            <a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a> |
            <span>Selamat Datang, {{ Auth::guard('mahasiswa')->user()->nama }}</span> |
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </nav>
    </header>

    <div style="margin-top: 20px; padding: 0 20px;">
        @if (session('success'))
            <p style="color: green; border: 1px solid green; padding: 10px;">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p style="color: red; border: 1px solid red; padding: 10px;">{{ session('error') }}</p>
        @endif
        @if (session('warning'))
            <p style="color: orange; border: 1px solid orange; padding: 10px;">{{ session('warning') }}</p>
        @endif

        @yield('content')
    </div>
</body>
</html>
