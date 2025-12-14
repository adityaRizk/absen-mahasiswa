@extends('layouts.admin')

@section('title', 'Form Rekap Absensi')

@section('content')
    <h2>Form Rekapitulasi Absensi Mahasiswa</h2>
    <p>Silakan pilih Mata Kuliah dan Kelas yang ingin direkap:</p>

    <form action="{{ route('admin.laporan.rekap.generate') }}" method="POST">
        @csrf

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
            @error('kode_matkul') <p style="color: red;">{{ $message }}</p> @enderror
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
            @error('kode_kelas') <p style="color: red;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" style="background-color: blue; color: white; padding: 10px;">Generate Laporan</button>
    </form>
@endsection
