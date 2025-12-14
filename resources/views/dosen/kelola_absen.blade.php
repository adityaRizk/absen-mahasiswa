@extends('layouts.dosen')

@section('title', 'Kelola Pertemuan Ke-' . $sesi->pertemuan)

@section('content')
    <a href="{{ route('dosen.detail.jadwal', $sesi->id_jadwal) }}">Kembali ke Detail Jadwal</a>

    <h2>Kelola Pertemuan Ke-{{ $sesi->pertemuan }}</h2>
    <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
        <p><strong>Mata Kuliah:</strong> {{ $sesi->jadwal->matkul->nama_matkul }}</p>
        <p><strong>Kelas:</strong> {{ $sesi->jadwal->kelas->kode_kelas }}</p>
        <p><strong>Status Sesi:</strong>
            @if ($sesi->jam_keluar === null)
                <span style="color: orange; font-weight: bold;">AKTIF</span> (Dibuka: {{ \Carbon\Carbon::parse($sesi->jam_masuk)->format('H:i') }})
            @else
                <span style="color: green;">SELESAI</span> (Ditutup: {{ \Carbon\Carbon::parse($sesi->jam_keluar)->format('H:i') }})
            @endif
        </p>
    </div>

    <hr>

    <h3>keterangan Pertemuan</h3>
    <form action="{{ route('dosen.update.sesi', $sesi->id_absen_dosen) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="keterangan">Isi keterangan Pertemuan:</label><br>
            <textarea id="keterangan" name="keterangan" rows="5" cols="50" required
                      {{ $sesi->jam_keluar !== null ? 'disabled' : '' }}>{{ old('keterangan', $sesi->keterangan) }}</textarea>
            @error('keterangan') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        @if ($sesi->jam_keluar === null)
            <div style="margin-bottom: 20px;">
                <button type="submit" name="action" value="update_only"
                        style="background-color: blue; color: white; padding: 8px;">
                    Simpan keterangan
                </button>

                <button type="submit" name="action" value="tutup_sesi"
                        onclick="return confirm('Apakah Anda yakin ingin menyimpan keterangan dan menutup sesi ini?')"
                        style="background-color: #dc3545; color: white; padding: 8px;">
                    Simpan & Tutup Sesi
                </button>
            </div>
        @else
            <p style="color: green; font-weight: bold;">keterangan sudah tersimpan dan sesi sudah ditutup.</p>
        @endif
    </form>

    <hr>

    <h3>Daftar Absensi Mahasiswa</h3>
    <p>Total Mahasiswa di kelas: {{ $mahasiswas->count() }}</p>

    <form action="{{ route('dosen.update.absen', $sesi->id_absen_dosen) }}" method="POST">
        @csrf
        @method('PUT')

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Waktu Absen (Otomatis)</th>
                    <th>Status Absen</th>
                    <th>Catatan (Manual)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswas as $mhs)
                @php
                    $statusSaatIni = $absensiTercatat[$mhs->nim] ?? 'Alpa';
                    $jamAbsen = $sesi->absenMahasiswa->where('nim', $mhs->nim)->first()->jam_absen ?? null;
                @endphp
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $jamAbsen ? \Carbon\Carbon::parse($jamAbsen)->format('H:i:s') : '-' }}</td>
                    <td>
                        <select name="status[{{ $mhs->nim }}]" {{ $sesi->jam_keluar !== null ? 'disabled' : '' }}>
                            <option value="Hadir" {{ $statusSaatIni == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="Izin" {{ $statusSaatIni == 'Izin' ? 'selected' : '' }}>Izin</option>
                            <option value="Sakit" {{ $statusSaatIni == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Alpa" {{ $statusSaatIni == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                        </select>
                    </td>
                    <td>
                        @if ($statusSaatIni == 'Alpa')
                             <span style="color: red;">[Default]</span>
                        @elseif ($jamAbsen)
                             <span style="color: green;">[Otomatis]</span>
                        @else
                             <span style="color: blue;">[Manual]</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if ($sesi->jam_keluar === null)
            <div style="margin-top: 20px;">
                <button type="submit" style="background-color: blue; color: white; padding: 10px;">
                    Simpan Perubahan Absensi Manual
                </button>
            </div>
        @else
            <p style="margin-top: 20px; color: grey;">Absensi tidak dapat diubah karena sesi sudah ditutup.</p>
        @endif
    </form>
@endsection
