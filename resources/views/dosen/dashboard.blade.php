@extends('layouts.dosen')

@section('title', 'Dashboard Dosen')

@section('content')
    <h2>Semua Jadwal Mengajar Anda</h2>
    <p>Total Jadwal Terdaftar: <strong>{{ $semuaJadwal->count() }}</strong></p>

    @if($semuaJadwal->isEmpty())
        <p style="border: 1px solid blue; padding: 10px;">Anda belum memiliki jadwal mengajar yang terdaftar.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Waktu</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($semuaJadwal as $jadwal)
                    @php
                        // Menentukan highlight jika hari ini
                        $isHariIni = ($jadwal->hari == $hariIni);
                    @endphp
                    <tr style="{{ $isHariIni ? 'background-color: #d4edda; font-weight: bold;' : '' }}">
                        <td>{{ $jadwal->hari }} {{ $isHariIni ? '(HARI INI)' : '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                        <td>{{ $jadwal->matkul->nama_matkul }} ({{ $jadwal->matkul->kode_matkul }})</td>
                        <td>{{ $jadwal->kelas->kode_kelas }}</td>
                        <td>
                            <a href="{{ route('dosen.detail.jadwal', $jadwal->id_jadwal) }}"
                               style="background-color: blue; color: white; padding: 5px; text-decoration: none;">
                                Lihat Detail & Pertemuan
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
