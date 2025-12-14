@extends('layouts.admin')

@section('title', 'Edit Kelas: ' . $kelas->kode_kelas)

@section('content')
    <h2>Edit Data Kelas: {{ $kelas->kode_kelas }}</h2>
    <a href="{{ route('admin.datamaster.kelas.index') }}">Kembali ke Daftar Kelas</a>
    <hr>

    <form action="{{ route('admin.datamaster.kelas.update', $kelas->kode_kelas) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="kode_kelas">Kode Kelas:</label><br>
            <input type="text" id="kode_kelas" name="kode_kelas" value="{{ $kelas->kode_kelas }}" disabled style="background-color: #eee;">
            <p style="font-size: 0.8em; color: grey;">Kode Kelas tidak dapat diubah.</p>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="jurusan">Jurusan:</label><br>
            <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan', $kelas->jurusan) }}" required>
            @error('jurusan') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="semester">Semester (Angka):</label><br>
            <input type="number" id="semester" name="semester" value="{{ old('semester', $kelas->semester) }}" min="1" max="8" required>
            @error('semester') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Perubahan</button>
    </form>
@endsection
