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
            <h3>Total Mata Kuliah</h3>
            <p style="font-size: 24px;">{{ $totalMatkul }}</p>
            <a href="{{ route('admin.datamaster.matkul.index') }}">Lihat Detail</a>
        </div>
        <div style="border: 1px solid #ccc; padding: 15px;">
            <h3>Total Jadwal</h3>
            <p style="font-size: 24px;">{{ $totalJadwal }}</p>
            <a href="{{ route('admin.datamaster.jadwal.index') }}">Lihat Detail</a>
        </div>
    </div>

    <hr>
@endsection
