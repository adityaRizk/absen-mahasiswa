@extends('layouts.dosen')

@section('title', 'Detail Jadwal: ' . $jadwal->matkul->nama_matkul)

@section('content')
    <a href="{{ route('dosen.dashboard') }}">Kembali ke Dashboard</a>

    <h2>Detail Jadwal Perkuliahan</h2>
    <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
        <p><strong>Mata Kuliah:</strong> {{ $jadwal->matkul->nama_matkul }} ({{ $jadwal->matkul->kode_matkul }})</p>
        <p><strong>Kelas:</strong> {{ $jadwal->kelas->kode_kelas }}</p>
        <p><strong>Hari/Waktu:</strong> {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
        <p><strong>Pertemuan Terakhir:</strong> {{ $pertemuanBerikutnya - 1 }}</p>
    </div>

    <h3>Aksi Sesi Absensi</h3>

    @php
        // Cek apakah ada sesi aktif yang belum ditutup
        $sesiAktif = $jadwal->absenDosen->whereNull('jam_keluar')->first();
    @endphp

    @if ($sesiAktif)
        <div style="background-color: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeeba; margin-bottom: 20px;">
            <p style="font-weight: bold;">[Sesi Pertemuan Ke-{{ $sesiAktif->pertemuan }} Sedang AKTIF]</p>
            <p>Dibuka: {{ \Carbon\Carbon::parse($sesiAktif->jam_masuk)->format('d M Y H:i:s') }}</p>

            <a href="{{ route('dosen.kelola.absen', $sesiAktif->id_absen_dosen) }}"
               style="background-color: orange; color: black; padding: 8px; text-decoration: none; margin-right: 10px;">
                Lihat & Tutup Sesi
            </a>
        </div>
    @else
        <form action="{{ route('dosen.buka.sesi', $jadwal->id_jadwal) }}" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="pertemuan" value="{{ $pertemuanBerikutnya }}">
            <button type="submit" onclick="return confirm('Buka sesi Pertemuan Ke-{{ $pertemuanBerikutnya }}?')"
                    style="background-color: green; color: white; padding: 10px;">
                Buka Sesi Pertemuan Ke-{{ $pertemuanBerikutnya }}
            </button>
        </form>
    @endif

    <hr>

    <h3>Riwayat Pertemuan (Total: {{ $jadwal->absenDosen->count() }})</h3>

    @if($jadwal->absenDosen->isEmpty())
        <p>Belum ada riwayat pertemuan yang terekam.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Pertemuan Ke-</th>
                    <th>Status</th>
                    <th>Tanggal Buka</th>
                    <th>Keterangan</th>
                    <th>Aksi Kelola</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwal->absenDosen as $sesi)
                <tr>
                    <td>{{ $sesi->pertemuan }}</td>
                    <td>
                        @if ($sesi->jam_keluar === null)
                            <span style="color: orange; font-weight: bold;">[AKTIF]</span>
                        @else
                            <span style="color: green;">[SELESAI]</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($sesi->jam_masuk)->isoFormat('D MMMM Y, H:mm') }}</td>
                    <td>{{ Str::limit($sesi->keterangan ?? 'Belum Diisi', 50) }}</td>
                    <td>
                        <a href="{{ route('dosen.kelola.absen', $sesi->id_absen_dosen) }}"
                           style="background-color: {{ $sesi->jam_keluar === null ? 'orange' : 'teal' }}; color: white; padding: 5px; text-decoration: none;">
                            Kelola Absen & Keterangan
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
