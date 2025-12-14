@extends('layouts.admin')

@section('title', 'Daftar Mahasiswa')

@section('content')
    <h2>Daftar Data Mahasiswa</h2>
    <p>Manajemen data seluruh mahasiswa di sistem absensi.</p>

    <a href="{{ route('admin.datamaster.mahasiswa.create') }}"
       style="background-color: green; color: white; padding: 10px; text-decoration: none; display: inline-block; margin-bottom: 20px;">
        + Tambah Mahasiswa Baru
    </a>

    @if($mahasiswas->isEmpty())
        <p style="border: 1px solid blue; padding: 10px;">Belum ada data mahasiswa yang tercatat.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kelas</th>
                    <th>Email</th>
                    <th>Tgl Lahir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswas as $mahasiswa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mahasiswa->nim }}</td>
                        <td>{{ $mahasiswa->nama }}</td>
                        <td>{{ $mahasiswa->kelas->kode_kelas ?? 'N/A' }}</td>
                        <td>{{ $mahasiswa->email }}</td>
                        <td>{{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.datamaster.mahasiswa.edit', $mahasiswa->nim) }}"
                               style="background-color: orange; color: white; padding: 5px; text-decoration: none; margin-right: 5px;">
                                Edit
                            </a>

                            <form action="{{ route('admin.datamaster.mahasiswa.destroy', $mahasiswa->nim) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Anda yakin ingin menghapus data Mahasiswa {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})?')"
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
