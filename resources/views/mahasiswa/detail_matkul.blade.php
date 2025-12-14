@extends('layouts.mahasiswa')

@section('title', 'Riwayat Absensi: ' . $jadwal->matkul->nama_matkul)

@section('content')
    <a href="{{ route('mahasiswa.dashboard') }}">Kembali ke Daftar Mata Kuliah</a>

    <h2>Riwayat Absensi Mata Kuliah</h2>
    <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
        <p><strong>Mata Kuliah:</strong> {{ $jadwal->matkul->nama_matkul }} ({{ $jadwal->matkul->kode_matkul }})</p>
        <p><strong>Dosen:</strong> {{ $jadwal->dosen->nama }}</p>
        <p><strong>Jadwal:</strong> {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
    </div>

    @if($dataAbsensi->isEmpty())
        <p>Belum ada riwayat pertemuan yang diselesaikan oleh Dosen.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Pertemuan Ke-</th>
                    <th>Tanggal</th>
                    <th>Status Anda</th>
                    <th>Waktu Absen</th>
                    <th>Keterangan Dosen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataAbsensi as $data)
                <tr>
                    <td>{{ $data['pertemuan'] }}</td>
                    <td>{{ $data['tanggal'] }}</td>
                    <td>
                        @php
                            $color = match($data['status_absen']) {
                                'Hadir' => 'green',
                                'Izin', 'Sakit' => 'orange',
                                default => 'red', // Alpa
                            };
                        @endphp
                        <span style="color: white; background-color: {{ $color }}; padding: 3px 6px; border-radius: 3px; font-weight: bold;">
                            {{ $data['status_absen'] }}
                        </span>
                    </td>
                    <td>{{ $data['jam_absen'] }}</td>
                    <td>{{ Str::limit($data['keterangan_dosen'], 80, '...') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
