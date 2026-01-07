@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa Baru')

@section('content')
    <h2>Tambah Data Mahasiswa Baru</h2>
    <a href="{{ route('admin.datamaster.mahasiswa.index') }}">Kembali ke Daftar Mahasiswa</a>
    <hr>

    <form action="{{ route('admin.datamaster.mahasiswa.store') }}" method="POST" class="data-master-form">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="nim">NIM (Nomor Induk Mahasiswa):</label><br>
            <input type="text" id="nim" name="nim" value="{{ old('nim') }}" maxlength="8" required>
            @error('nim') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="kode_kelas">Kelas:</label><br>
            <select id="kode_kelas" name="kode_kelas" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $kelasItem)
                    <option value="{{ $kelasItem->kode_kelas }}" {{ old('kode_kelas') == $kelasItem->kode_kelas ? 'selected' : '' }}>
                        {{ $kelasItem->kode_kelas }} ({{ $kelasItem->jurusan }})
                    </option>
                @endforeach
            </select>
            @error('kode_kelas') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="nama">Nama Lengkap:</label><br>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required>
            @error('nama') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="tanggal_lahir">Tanggal Lahir:</label><br>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
            @error('tanggal_lahir') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password">Password Default:</label><br>
            <input type="password" id="password" name="password" required>
            @error('password') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Mahasiswa</button>
    </form>
@endsection
