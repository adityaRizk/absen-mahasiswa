@extends('layouts.admin')

@section('title', 'Tambah Jadwal Mengajar')

@section('content')
    <h2>Tambah Jadwal Mengajar Baru</h2>
    <a href="{{ route('admin.datamaster.jadwal.index') }}">Kembali ke Daftar Jadwal</a>
    <hr>

    <form action="{{ route('admin.datamaster.jadwal.store') }}" method="POST" class="data-master-form">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="nip">Dosen Pengampu:</label><br>
            <select id="nip" name="nip" required>
                <option value="">-- Pilih Dosen --</option>
                @foreach($dosens as $dosen)
                    <option value="{{ $dosen->nip }}" {{ old('nip') == $dosen->nip ? 'selected' : '' }}>
                        {{ $dosen->nama }} ({{ $dosen->nip }})
                    </option>
                @endforeach
            </select>
            @error('nip') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="kode_matkul">Mata Kuliah:</label><br>
            <select id="kode_matkul" name="kode_matkul" required>
                <option value="">-- Pilih Mata Kuliah --</option>
                @foreach($matkuls as $matkul)
                    <option value="{{ $matkul->kode_matkul }}" {{ old('kode_matkul') == $matkul->kode_matkul ? 'selected' : '' }}>
                        {{ $matkul->nama_matkul }} ({{ $matkul->kode_matkul }})
                    </option>
                @endforeach
            </select>
            @error('kode_matkul') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
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
            <label for="hari">Hari:</label><br>
            <select id="hari" name="hari" required>
                <option value="">-- Pilih Hari --</option>
                @foreach($daftarHari as $id)
                    <option value="{{ $id }}" {{ old('hari') == $id ? 'selected' : '' }}>
                        {{ $id }}
                    </option>
                @endforeach
            </select>
            @error('hari') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="jam_mulai">Jam Mulai (HH:MM:SS):</label><br>
            <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" step="1" required>
            @error('jam_mulai') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="jam_selesai">Jam Selesai (HH:MM:SS):</label><br>
            <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" step="1" required>
            @error('jam_selesai') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="ruangan">Ruangan (Opsional):</label><br>
            <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan') }}" maxlength="10">
            @error('ruangan') <p style="color: red; margin: 0;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Simpan Jadwal</button>
    </form>
@endsection
