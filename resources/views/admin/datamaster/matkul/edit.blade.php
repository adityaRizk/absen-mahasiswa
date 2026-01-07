@extends('layouts.admin')

@section('title', 'Edit Mata Kuliah: ' . $matkul->kode_matkul)

@section('content')
    <h2>Edit Data Mata Kuliah: {{ $matkul->kode_matkul }}</h2>
    <a href="{{ route('admin.datamaster.matkul.index') }}">Kembali ke Daftar Mata Kuliah</a>
    <hr>

    <form action="{{ route('admin.datamaster.matkul.update', $matkul->kode_matkul) }}" method="POST" class="data-master-form">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="kode_matkul">Kode Mata Kuliah:</label><br>
            <input type="text" id="kode_matkul" name="kode_matkul" value="{{ $matkul->kode_matkul }}" disabled style="background-color: #eee;">
            <p style="font-size: 0.8em; color: grey;">Kode Mata Kuliah tidak dapat diubah.</p>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="nama_matkul">Nama Mata Kuliah:</label><br>
            <input type="text" id="nama_matkul" name="nama_matkul" value="{{ old('nama_matkul', $matkul->nama_matkul) }}" required>
            @error('nama_matkul') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="sks">SKS (Satuan Kredit Semester):</label><br>
            <input type="number" id="sks" name="sks" value="{{ old('sks', $matkul->sks) }}" min="1" max="6" required>
            @error('sks') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Perubahan</button>
    </form>
@endsection
