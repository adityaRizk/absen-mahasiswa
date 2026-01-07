@extends('layouts.admin')

@section('title', 'Tambah Dosen')

@section('content')
    <h2>Tambah Data Dosen Baru</h2>
    <a href="{{ route('admin.datamaster.dosen.index') }}">Kembali ke Daftar Dosen</a>
    <hr>

    <form action="{{ route('admin.datamaster.dosen.store') }}" method="POST" class="data-master-form">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="nip">NIP (Nomor Induk Pegawai):</label><br>
            <input type="text" id="nip" name="nip" value="{{ old('nip') }}" required>
            @error('nip') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="nama">Nama Lengkap:</label><br>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required>
            @error('nama') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="no_telp">Nomor Telepon:</label><br>
            <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}">
            @error('no_telp') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password">Password Default:</label><br>
            <input type="password" id="password" name="password" required>
            @error('password') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Dosen</button>
    </form>
@endsection
