@extends('layouts.app')

@section('title','Tambah Employee')

@section('content')
<style>
    .box {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .box-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    label {
        display:block;
        font-size:14px;
        margin-bottom:6px;
    }

    input {
        width:100%;
        padding:10px;
        border-radius:8px;
        border:1px solid #d1d5db;
        font-size:14px;
    }

    .actions {
        display:flex;
        gap:10px;
        margin-top:20px;
    }

    button {
        padding:10px 18px;
        border:none;
        border-radius:8px;
        cursor:pointer;
        background:#2563eb;
        color:white;
    }

    a {
        padding:10px 18px;
        border-radius:8px;
        background:#e5e7eb;
        color:#111;
        text-decoration:none;
    }

    .error {
        color:#dc2626;
        font-size:13px;
        margin-top:4px;
    }
</style>

<div class="box">
    <div class="box-title">➕ Tambah Employee</div>

    <form method="POST" action="{{ route('master.employees.store') }}">
        @csrf

        <div class="form-group">
            <label>NIK</label>
            <input type="text" name="nik" value="{{ old('nik') }}">
            @error('nik') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="{{ old('nama') }}">
            @error('nama') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="actions">
            <button type="submit">Simpan</button>
            <a href="{{ route('master.employees') }}">Batal</a>
        </div>
    </form>
</div>
@endsection
