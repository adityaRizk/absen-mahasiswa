@extends('layouts.admin')

@section('title', 'Manajemen Dosen')

@section('content')
    <h2>Manajemen Data Dosen</h2>
    <p>Daftar seluruh dosen pengajar dan kredensial login.</p>

    <a href="{{ route('admin.datamaster.dosen.create') }}"
       style="background-color: green; color: white; padding: 10px; text-decoration: none; display: inline-block; margin-bottom: 20px;">
        + Tambah Dosen Baru
    </a>
    <hr>

    @if($dosens->isEmpty())
        <p style="border: 1px solid blue; padding: 10px;">Data Dosen kosong. Silakan tambahkan data baru.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dosens as $dosen)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dosen->nip }}</td>
                    <td>{{ $dosen->nama }}</td>
                    <td>{{ $dosen->email }}</td>
                    <td>{{ $dosen->no_telp ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.datamaster.dosen.edit', $dosen->nip) }}"
                           style="background-color: orange; color: white; padding: 5px; text-decoration: none; margin-right: 5px;">
                            Edit
                        </a>

                        <form action="{{ route('admin.datamaster.dosen.destroy', $dosen->nip) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Yakin ingin menghapus Dosen {{ $dosen->nama }}? Semua jadwal terkait akan terhapus!')"
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
