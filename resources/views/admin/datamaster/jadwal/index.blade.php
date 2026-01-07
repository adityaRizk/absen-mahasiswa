@extends('layouts.admin')

@section('title', 'Manajemen Jadwal Mengajar')

@section('content')
    <h2>Manajemen Jadwal Mengajar</h2>
    <p>Daftar jadwal perkuliahan yang ditetapkan.</p>

    <a href="{{ route('admin.datamaster.jadwal.create') }}"
       style="background-color: green; color: white; padding: 10px; text-decoration: none; display: inline-block; margin-bottom: 20px;">
        + Tambah Jadwal Baru
    </a>
    <hr>

    @if($jadwals->isEmpty())
        <p style="border: 1px solid blue; padding: 10px;">Data Jadwal kosong. Silakan tambahkan jadwal baru.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Matkul</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Dosen Pengampu</th>
                    <th>Hari</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwals as $jadwal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jadwal->kode_matkul }}</td>
                    <td>{{ $jadwal->matkul->nama_matkul ?? 'Data Hilang' }}</td>
                    <td>{{ $jadwal->kelas->kode_kelas ?? 'Data Hilang' }}</td>
                    <td>{{ $jadwal->dosen->nama ?? 'Data Hilang' }}</td>
                    <td>{{ $jadwal->hari }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.datamaster.jadwal.edit', $jadwal->id_jadwal) }}" style="background-color: orange; color: white; padding: 5px; text-decoration: none; margin-right: 5px;">
                            Edit
                        </a>

                        <form action="{{ route('admin.datamaster.jadwal.destroy', $jadwal->id_jadwal) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('PERINGATAN! Menghapus jadwal akan menghapus semua data ABSENSI terkait. Lanjutkan?')"
                                    style="background-color: red; color: white; padding: 5px; border: none; cursor: pointer;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
