@extends('layouts.admin')

@section('title', 'Edit Dosen: ' . $dosen->nama)

@section('content')
    <h2>Edit Data Dosen: {{ $dosen->nama }}</h2>
    <a href="{{ route('admin.datamaster.dosen.index') }}">Kembali ke Daftar Dosen</a>
    <hr>

    <form action="{{ route('admin.datamaster.dosen.update', $dosen->nip) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="nip">NIP:</label><br>
            <input type="text" id="nip" name="nip" value="{{ $dosen->nip }}" disabled style="background-color: #eee;">
            <p style="font-size: 0.8em; color: grey;">NIP tidak dapat diubah.</p>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="nama">Nama Lengkap:</label><br>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $dosen->nama) }}" required>
            @error('nama') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="{{ old('email', $dosen->email) }}" required>
            @error('email') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="no_telp">Nomor Telepon:</label><br>
            <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $dosen->no_telp) }}">
            @error('no_telp') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password_baru">Password Baru (Kosongkan jika tidak ingin diubah):</label><br>
            <input type="password" id="password_baru" name="password_baru">
            @error('password_baru') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Perubahan</button>
    </form>
@endsection
