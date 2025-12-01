@extends('layouts.dosen')

@section('title', 'Sesi Absensi Aktif')

@section('content')
    <a href="{{ route('dosen.dashboard') }}">Kembali ke Dashboard</a>

    <h3>Sesi Perkuliahan: {{ $sesi->jadwal->matkul->nama_matkul }} ({{ $sesi->jadwal->kelas->kode_kelas }})</h3>
    <p>
        Pertemuan Ke: <strong>{{ $sesi->pertemuan }}</strong> <br>
        Dibuka pada: <strong>{{ \Carbon\Carbon::parse($sesi->jam_masuk)->format('d M Y H:i:s') }}</strong>
        @if($sesi->jam_keluar)
            <br>Ditutup pada: <strong>{{ \Carbon\Carbon::parse($sesi->jam_keluar)->format('d M Y H:i:s') }}</strong>
        @endif
    </p>

    <hr>

    <h4>Daftar Absensi Mahasiswa (Total: {{ $absensiMahasiswa->count() }})</h4>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Waktu Absen</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensiMahasiswa as $absen)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $absen->mahasiswa->nim }}</td>
                <td>{{ $absen->mahasiswa->nama }}</td>
                <td>{{ \Carbon\Carbon::parse($absen->jam_absen)->format('H:i:s') }}</td>
                <td>[{{ $absen->status_absen }}]</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada mahasiswa yang absen.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
