@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <h2>Selamat Datang di Dashboard Admin</h2>
    <p>Ringkasan Statistik Data Master:</p>

    <div style="display: flex; gap: 20px;">
        <div style="border: 1px solid #ccc; padding: 15px;">
            <h3>Total Dosen</h3>
            <p style="font-size: 24px;">{{ $totalDosen }}</p>
            <a href="{{ route('admin.datamaster.dosen.index') }}">Lihat Detail</a>
        </div>
        <div style="border: 1px solid #ccc; padding: 15px;">
            <h3>Total Mahasiswa</h3>
            <p style="font-size: 24px;">{{ $totalMahasiswa }}</p>
            <a href="{{ route('admin.datamaster.mahasiswa.index') }}">Lihat Detail</a>
        </div>
        <div style="border: 1px solid #ccc; padding: 15px;">
            <h3>Total Kelas</h3>
            <p style="font-size: 24px;">{{ $totalKelas }}</p>
            <a href="{{ route('admin.datamaster.kelas.index') }}">Lihat Detail</a>
        </div>
        <div style="border: 1px solid #ccc; padding: 15px;">
            <h3>Total Jadwal</h3>
            <p style="font-size: 24px;">{{ $totalJadwal }}</p>
            {{-- <a href="{{ route('admin.datamaster.jadwal.index') }}">Lihat Detail</a> --}}
        </div>
    </div>

    <hr>

    <h3>Top 5 Mahasiswa Paling Rajin Absen</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="40%">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Total Hadir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topKehadiran as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nim }}</td>
                <td>{{ $item->mahasiswa->nama }}</td>
                <td>{{ $item->total_hadir }} kali</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Belum ada data absensi yang terekam.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
