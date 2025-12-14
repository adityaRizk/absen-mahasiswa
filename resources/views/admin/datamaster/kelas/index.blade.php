@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('content')
    <h2>Manajemen Data Kelas</h2>
    <p>Daftar seluruh kelas yang terdaftar dalam sistem.</p>

    <a href="{{ route('admin.datamaster.kelas.create') }}"
       style="background-color: green; color: white; padding: 10px; text-decoration: none; display: inline-block; margin-bottom: 20px;">
        + Tambah Kelas Baru
    </a>
    <hr>

    @if($kelas->isEmpty())
        <p style="border: 1px solid blue; padding: 10px;">Data Kelas kosong. Silakan tambahkan data baru.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Kelas</th>
                    <th>Jurusan</th>
                    <th>Semester</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelas as $kelasItem)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $kelasItem->kode_kelas }}</td>
                    <td>{{ $kelasItem->jurusan }}</td>
                    <td>{{ $kelasItem->semester }}</td>
                    <td>
                        <a href="{{ route('admin.datamaster.kelas.edit', $kelasItem->kode_kelas) }}"
                           style="background-color: orange; color: white; padding: 5px; text-decoration: none; margin-right: 5px;">
                            Edit
                        </a>

                        <form action="{{ route('admin.datamaster.kelas.destroy', $kelasItem->kode_kelas) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('PERINGATAN! Menghapus kelas akan menghapus semua Mahasiswa dan Jadwal terkait. Lanjutkan?')"
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
