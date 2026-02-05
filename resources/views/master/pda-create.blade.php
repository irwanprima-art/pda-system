@extends('layouts.app')

@section('title','Tambah PDA')

@section('content')
<style>
    .form-box {
        max-width: 520px;
        margin: 0 auto;
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .form-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    label {
        display:block;
        margin-bottom:6px;
        font-weight:500;
    }

    input, select {
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

    .btn {
        padding:10px 18px;
        border-radius:8px;
        border:none;
        cursor:pointer;
        font-size:14px;
        text-decoration:none;
        display:inline-block;
    }

    .btn-save {
        background:#16a34a;
        color:white;
    }

    .btn-cancel {
        background:#e5e7eb;
        color:#111;
    }

    .error {
        color:#dc2626;
        font-size:13px;
        margin-top:4px;
    }
</style>

<div class="form-box">

    <div class="form-title">
        ➕ Tambah PDA
    </div>

    <form method="POST" action="{{ route('master.pdas.store') }}">
        @csrf

        <div class="form-group">
            <label>No PDA</label>
            <input
                type="text"
                name="pda_no"
                value="{{ old('pda_no') }}"
                placeholder="Contoh: PDA-001"
                required
            >
            @error('pda_no')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="available">Available</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <div class="actions">
            <button class="btn btn-save">Simpan</button>
            <a href="{{ route('master.pdas') }}" class="btn btn-cancel">Batal</a>
        </div>

    </form>

</div>
@endsection
