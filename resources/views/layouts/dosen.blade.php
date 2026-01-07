<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Dosen') | Sistem Absensi</title>
    <style>
/* Styling Umum */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f7f9fc; /* Latar belakang body sangat terang */
    color: #333;
}

/* 1. HEADER (Navigation Bar) */
header {
    background-color: #1e8449; /* Hijau Dosen (Warna dominan yang berbeda dari Admin) */
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
    color: #d0f0d0;
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
    background-color: #166539;
}

/* Garis pemisah antara link dan info user */
nav > span {
    margin-left: 20px;
    padding-left: 15px;
    border-left: 1px solid #7f8c8d;
    color: #d0f0d0; /* Warna hijau terang */
    font-weight: 500;
}

/* Tombol Logout */
nav form button {
    background-color: #c0392b; /* Merah Marun untuk Logout */
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
    transition: background-color 0.3s;
}

nav form button:hover {
    background-color: #a93226;
}


/* 2. MAIN CONTENT & ALERTS */
.main-content {
    padding: 30px;
    max-width: 1200px;
    margin: 25px auto;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
}

h2 {
    color: #1e8449;
    border-bottom: 2px solid #e0f8e0;
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


/* 3. STYLING TABEL DAN TOMBOL KHUSUS DOSEN */

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

table thead tr {
    background-color: #1e8449; /* Warna header tabel sesuai Dosen */
    color: white;
}

table tbody tr:nth-child(even) {
    background-color: #f8f8f8;
}

table tbody tr:hover {
    background-color: #f0f0f0;
}

/* Styling untuk highlight baris hari ini (hijau muda) */
tr[style*="background-color: #d4edda"] {
    background-color: #c8e6c9 !important;
    border-left: 5px solid #1e8449;
}

/* Button & Link Aksi */
.main-content a, .main-content button {
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9em;
    font-weight: 600;
    transition: opacity 0.3s;
}

.main-content a:hover, .main-content button:hover:not(:disabled) {
    opacity: 0.8;
}

/* Tombol Biru (Aksi Detail/Kelola) */
a[style*="background-color: blue"], button[style*="background-color: blue"] {
    background-color: #007bff !important;
    color: white !important;
}

/* Tombol Hijau (Aksi Buka Sesi) */
button[style*="background-color: green"] {
    background-color: #28a745 !important;
    color: white !important;
}

/* Tombol Merah/Danger (Tutup Sesi) */
button[style*="background-color: #dc3545"] {
    background-color: #dc3545 !important;
    color: white !important;
}

/* Styling Khusus Sesi Aktif (Kuning/Orange) */
div[style*="background-color: #fff3cd"] {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
    padding: 15px;
    border-radius: 6px;
}

/* Tombol Disabled */
button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}
    </style>
</head>
<body>
    <header>
        <h1><a href="{{ route('dosen.dashboard') }}">Dosen Panel</a></h1>
        <nav>
            <a href="{{ route('dosen.dashboard') }}">Dashboard (Semua Jadwal)</a> |

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
