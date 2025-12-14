@extends('layouts.admin')

@section('title', 'Hasil Rekap Absensi')

@section('content')
    <h2>Hasil Rekapitulasi Absensi</h2>
    <a href="{{ route('admin.laporan.rekap.form') }}">Kembali ke Form</a>
    <hr>

    <div style="margin-bottom: 20px;">
        <p>Mata Kuliah: <strong>{{ $jadwal->matkul->nama_matkul }}</strong></p>
        <p>Kelas: <strong>{{ $jadwal->kelas->kode_kelas }}</strong></p>
        <p>Dosen: <strong>{{ $jadwal->dosen->nama }}</strong></p>
        <p>Total Pertemuan Tercatat: <strong>{{ $totalPertemuan }}</strong></p>
    </div>

    <h3>Tabel Rekap Kehadiran</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIM</th>
                <th rowspan="2">Nama Mahasiswa</th>
                <th colspan="4" style="text-align: center;">Jumlah Kehadiran</th>
                <th rowspan="2">Persentase Hadir</th>
                <th rowspan="2">Keterangan</th>
            </tr>
            <tr>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapAbsensi as $rekap)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rekap['nim'] }}</td>
                <td>{{ $rekap['nama'] }}</td>
                <td style="text-align: center;">{{ $rekap['hadir'] }}</td>
                <td style="text-align: center;">{{ $rekap['sakit'] }}</td>
                <td style="text-align: center;">{{ $rekap['izin'] }}</td>
                <td style="text-align: center;">{{ $rekap['alpa'] }}</td>
                <td style="text-align: center;">{{ $rekap['persentase'] }}%</td>
                <td>
                    @if($rekap['persentase'] < 80)
                        <strong style="color: red;">Tidak Memenuhi Syarat Absensi (Kurang dari 80%)</strong>
                    @else
                        Memenuhi Syarat
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data mahasiswa atau sesi absensi untuk kriteria ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
