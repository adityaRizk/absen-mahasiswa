@extends('layouts.admin')

@section('title', 'Edit Mahasiswa: ' . $mahasiswa->nama)

@section('content')
    <h2>Edit Data Mahasiswa: {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</h2>
    <a href="{{ route('admin.datamaster.mahasiswa.index') }}">Kembali ke Daftar Mahasiswa</a>
    <hr>

    <form action="{{ route('admin.datamaster.mahasiswa.update', $mahasiswa->nim) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="nim">NIM:</label><br>
            <input type="text" id="nim" name="nim" value="{{ $mahasiswa->nim }}" disabled style="background-color: #eee;">
            <p style="font-size: 0.8em; color: grey;">NIM tidak dapat diubah.</p>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="kode_kelas">Kelas:</label><br>
            <select id="kode_kelas" name="kode_kelas" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $kelasItem)
                    <option value="{{ $kelasItem->kode_kelas }}"
                            {{ old('kode_kelas', $mahasiswa->kode_kelas) == $kelasItem->kode_kelas ? 'selected' : '' }}>
                        {{ $kelasItem->kode_kelas }} ({{ $kelasItem->jurusan }})
                    </option>
                @endforeach
            </select>
            @error('kode_kelas') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="nama">Nama Lengkap:</label><br>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required>
            @error('nama') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="tanggal_lahir">Tanggal Lahir:</label><br>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir) }}">
            @error('tanggal_lahir') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="{{ old('email', $mahasiswa->email) }}" required>
            @error('email') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password_baru">Password Baru (Kosongkan jika tidak ingin diubah):</label><br>
            <input type="password" id="password_baru" name="password_baru">
            @error('password_baru') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Perubahan</button>
    </form>
@endsection
