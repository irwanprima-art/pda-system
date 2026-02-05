@extends('layouts.app')

@section('title','Master Data')

@section('content')
<style>
    .master-container {
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .master-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .master-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
    }

    .master-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 22px;
        text-decoration: none;
        color: #111827;
        transition: all 0.2s ease;
        background: #f9fafb;
    }

    .master-card:hover {
        background: #eef2ff;
        border-color: #6366f1;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(99,102,241,0.2);
    }

    .master-card h3 {
        margin: 0 0 8px;
        font-size: 18px;
    }

    .master-card p {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }
</style>

<div class="master-container">

    <div class="master-title">
        🗂️ Master Data
    </div>

    <div class="master-grid">

        {{-- MASTER EMPLOYEE (TIDAK DIUBAH) --}}
        <a href="{{ route('master.employees') }}" class="master-card">
            <h3>👤 Master Employee</h3>
            <p>Kelola data karyawan (NIK, Nama)</p>
        </a>

        {{-- MASTER PDA (HANYA INI YANG DIUBAH) --}}
        <a href="{{ route('master.pdas') }}" class="master-card">
            <h3>📟 Master PDA</h3>
            <p>Kelola daftar PDA dan status perangkat</p>
        </a>

    </div>

</div>
@endsection
