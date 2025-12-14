@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <h2>Daftar Mata Kuliah Anda</h2>
    <p>NIM Anda: <strong>{{ $mahasiswa->nim }}</strong> | Kelas: <strong>{{ $mahasiswa->kode_kelas }}</strong></p>

    @if($semuaJadwal->isEmpty())
        <p style="border: 1px solid red; padding: 10px;">Anda tidak memiliki jadwal kuliah yang terdaftar.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>Kode Matkul</th>
                    <th>Dosen Pengampu</th>
                    <th>Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $hariIni = now()->format('l');
                @endphp
                @foreach($semuaJadwal as $jadwal)
                    @php
                        $isHariIni = ($jadwal->hari == $hariIni);
                    @endphp
                    <tr style="{{ $isHariIni ? 'background-color: #e6f7ff; font-weight: bold;' : '' }}">
                        <td>{{ $jadwal->matkul->nama_matkul }}</td>
                        <td>{{ $jadwal->matkul->kode_matkul }}</td>
                        <td>{{ $jadwal->dosen->nama }}</td>
                        <td>
                            {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            @if($isHariIni)
                                <span style="color: blue;">(HARI INI!)</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('mahasiswa.detail.matkul', $jadwal->id_jadwal) }}"
                               style="background-color: teal; color: white; padding: 5px; text-decoration: none;">
                                Lihat Riwayat Absensi
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @endsection
