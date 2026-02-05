@extends('layouts.app')

@section('title','Edit Employee')

@section('content')
<style>
    .box {
        max-width: 520px;
        margin: 0 auto;
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    label { font-weight:600; display:block; margin-bottom:6px }
    input, select {
        width:100%;
        padding:10px;
        margin-bottom:16px;
        border-radius:8px;
        border:1px solid #d1d5db;
    }

    button {
        padding:10px 18px;
        border:none;
        border-radius:8px;
        background:#2563eb;
        color:white;
        cursor:pointer;
    }

    a {
        margin-left:10px;
        text-decoration:none;
        color:#374151;
    }
</style>

<div class="box">
    <h2>✏️ Edit Employee</h2>

    <form method="POST" action="{{ route('master.employees.update', $employee->id) }}">
        @csrf
        @method('PUT')

        <label>NIK</label>
        <input type="text" name="nik" value="{{ old('nik', $employee->nik) }}" required>

        <label>Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $employee->nama) }}" required>

        <label>Status</label>
        <select name="status">
            <option value="aktif" {{ $employee->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ $employee->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <button type="submit">Simpan</button>
        <a href="{{ route('master.employees') }}">Batal</a>
    </form>
</div>
@endsection
