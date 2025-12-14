@extends('layouts.admin')

@section('title', 'Manajemen Mata Kuliah')

@section('content')
    <h2>Manajemen Data Mata Kuliah Dasar</h2>
    <p>Daftar seluruh mata kuliah yang tersedia di sistem.</p>

    <a href="{{ route('admin.datamaster.matkul.create') }}"
       style="background-color: green; color: white; padding: 10px; text-decoration: none; display: inline-block; margin-bottom: 20px;">
        + Tambah Mata Kuliah Baru
    </a>
    <hr>

    @if($matkuls->isEmpty())
        <p style="border: 1px solid blue; padding: 10px;">Data Mata Kuliah kosong. Silakan tambahkan data baru.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Matkul</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matkuls as $matkul)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $matkul->kode_matkul }}</td>
                    <td>{{ $matkul->nama_matkul }}</td>
                    <td>{{ $matkul->sks }}</td>
                    <td>
                        <a href="{{ route('admin.datamaster.matkul.edit', $matkul->kode_matkul) }}"
                           style="background-color: orange; color: white; padding: 5px; text-decoration: none; margin-right: 5px;">
                            Edit
                        </a>

                        <form action="{{ route('admin.datamaster.matkul.destroy', $matkul->kode_matkul) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('PERINGATAN! Menghapus matkul akan menghapus semua Jadwal terkait. Lanjutkan?')"
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
