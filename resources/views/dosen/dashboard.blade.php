@extends('layouts.dosen')

@section('title', 'Dashboard Dosen')

@section('content')
    <h2>Jadwal Mengajar Hari Ini ({{ now()->isoFormat('dddd, D MMMM Y') }})</h2>

    @if($jadwalHariIni->isEmpty())
        <p>Tidak ada jadwal mengajar untuk Anda hari ini.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Aksi Sesi Absensi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalHariIni as $jadwal)
                    @php
                        // Cek status sesi aktif dari relasi AbsenDosen
                        $sesiAktif = \App\Models\AbsenDosen::where('id_jadwal', $jadwal->id_jadwal)
                                                            ->whereDate('jam_masuk', now()->toDateString())
                                                            ->whereNull('jam_keluar')
                                                            ->first();
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                        <td>{{ $jadwal->matkul->nama_matkul }} ({{ $jadwal->matkul->kode_matkul }})</td>
                        <td>{{ $jadwal->kelas->kode_kelas }}</td>
                        <td>
                            @if($sesiAktif)
                                <form action="{{ route('dosen.absensi.tutup', $jadwal->id_jadwal) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menutup sesi absensi ini?')" style="background-color: orange; color: black;">
                                        Tutup Sesi
                                    </button>
                                </form>
                                <a href="{{ route('dosen.absensi.sesi-aktif', $jadwal->id_jadwal) }}" style="background-color: cyan; color: black; padding: 5px;">
                                    Lihat Absensi
                                </a>
                            @else
                                @php
                                    // Cek apakah sesi sudah ditutup sebelumnya hari ini
                                    $sesiSelesai = \App\Models\AbsenDosen::where('id_jadwal', $jadwal->id_jadwal)
                                                                        ->whereDate('jam_masuk', now()->toDateString())
                                                                        ->whereNotNull('jam_keluar')
                                                                        ->first();
                                @endphp

                                @if ($sesiSelesai)
                                    <button disabled>Sesi Selesai</button>
                                    <a href="{{ route('dosen.absensi.sesi-aktif', $jadwal->id_jadwal) }}">
                                        Lihat Rekap
                                    </a>
                                @else
                                    <form action="{{ route('dosen.absensi.buka', $jadwal->id_jadwal) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background-color: green; color: white;">
                                            Buka Sesi Absensi
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($sesiAktif)
                                [AKTIF]
                            @else
                                [TUTUP]
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
