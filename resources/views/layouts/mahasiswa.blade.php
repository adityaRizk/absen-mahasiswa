<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Mahasiswa')</title>
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
