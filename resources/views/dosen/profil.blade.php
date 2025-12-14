@extends('layouts.dosen')

@section('title', 'Profil Dosen')

@section('content')
    <h2>Informasi Profil Anda</h2>

    <div style="border: 1px solid #ccc; padding: 20px; width: 40%;">
        <p><strong>NIP:</strong> {{ $dosen->nip }}</p>
        <p><strong>Nama Lengkap:</strong> {{ $dosen->nama }}</p>
        <p><strong>Email:</strong> {{ $dosen->email }}</p>
        <p><strong>Nomor Telepon:</strong> {{ $dosen->no_telp ?? '-' }}</p>

        <hr>
        <a href="#" style="background-color: teal; color: white; padding: 8px; text-decoration: none;">Ubah Password</a>
    </div>
@endsection
