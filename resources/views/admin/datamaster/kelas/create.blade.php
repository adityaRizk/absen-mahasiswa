@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('content')
    <h2>Tambah Data Kelas Baru</h2>
    <a href="{{ route('admin.datamaster.kelas.index') }}">Kembali ke Daftar Kelas</a>
    <hr>

    <form action="{{ route('admin.datamaster.kelas.store') }}" method="POST" class="data-master-form">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="kode_kelas">Kode Kelas (e.g., TI3A):</label><br>
            <input type="text" id="kode_kelas" name="kode_kelas" value="{{ old('kode_kelas') }}" maxlength="4" required>
            @error('kode_kelas') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="jurusan">Jurusan:</label><br>
            <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan') }}" required>
            @error('jurusan') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="semester">Semester (Angka):</label><br>
            <input type="number" id="semester" name="semester" value="{{ old('semester') }}" min="1" max="8" required>
            @error('semester') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Kelas</button>
    </form>
@endsection
