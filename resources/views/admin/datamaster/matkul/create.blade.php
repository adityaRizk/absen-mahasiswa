@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')

@section('content')
    <h2>Tambah Data Mata Kuliah Baru</h2>
    <a href="{{ route('admin.datamaster.matkul.index') }}">Kembali ke Daftar Mata Kuliah</a>
    <hr>

    <form action="{{ route('admin.datamaster.matkul.store') }}" method="POST" class="data-master-form">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="kode_matkul">Kode Mata Kuliah (e.g., IF301):</label><br>
            <input type="text" id="kode_matkul" name="kode_matkul" value="{{ old('kode_matkul') }}" maxlength="10" required>
            @error('kode_matkul') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="nama_matkul">Nama Mata Kuliah:</label><br>
            <input type="text" id="nama_matkul" name="nama_matkul" value="{{ old('nama_matkul') }}" required>
            @error('nama_matkul') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="sks">SKS (Satuan Kredit Semester):</label><br>
            <input type="number" id="sks" name="sks" value="{{ old('sks') }}" min="1" max="6" required>
            @error('sks') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Mata Kuliah</button>
    </form>
@endsection
