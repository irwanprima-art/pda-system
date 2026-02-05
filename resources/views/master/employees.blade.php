@extends('layouts.app')

@section('title','Master Employee')

@section('content')
<style>
    .box {
        max-width: 1000px;
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
        display:flex;
        justify-content:space-between;
        align-items:center;
    }

    .search-row {
        display:flex;
        gap:10px;
        margin-bottom:18px;
    }

    .search-row input {
        padding:10px;
        width:280px;
        border-radius:8px;
        border:1px solid #d1d5db;
    }

    .search-row button {
        padding:10px 18px;
        border:none;
        border-radius:8px;
        background:#2563eb;
        color:white;
        cursor:pointer;
    }

    .search-row a {
        padding:10px 18px;
        border-radius:8px;
        background:#e5e7eb;
        color:#111;
        text-decoration:none;
    }

    table {
        width:100%;
        border-collapse:collapse;
    }

    th, td {
        border:1px solid #e5e7eb;
        padding:10px;
        text-align:left;
    }

    th {
        background:#f3f4f6;
    }

    .empty {
        text-align:center;
        color:#6b7280;
        padding:30px;
    }

    /* =========================
       PAGINATION (FIX TAMPILAN)
    ========================= */
    .pagination {
        display:flex;
        justify-content:center;
        gap:6px;
        margin-top:25px;
        flex-wrap:wrap;
    }

    .pagination li {
        list-style:none;
    }

    .pagination li a,
    .pagination li span {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:36px;
        height:36px;
        padding:0 12px;
        border-radius:8px;
        border:1px solid #e5e7eb;
        text-decoration:none;
        font-size:14px;
        color:#374151;
        background:#ffffff;
        transition:all 0.2s ease;
    }

    .pagination li a:hover {
        background:#eef2ff;
        border-color:#6366f1;
        color:#4338ca;
    }

    .pagination li.active span {
        background:#2563eb;
        color:white;
        border-color:#2563eb;
        font-weight:600;
    }

    .pagination li.disabled span {
        color:#9ca3af;
        background:#f3f4f6;
        cursor:not-allowed;
    }
</style>

<div class="box">

    <div class="box-title">
        👤 Master Employee
        <a href="{{ route('master.employees.create') }}"
           style="padding:8px 14px; background:#16a34a; color:white; border-radius:8px; text-decoration:none; font-size:14px">
            + Tambah
        </a>
    </div>

    <!-- 🔍 SEARCH -->
    <form method="GET" class="search-row">
        <input
            type="text"
            name="keyword"
            placeholder="Cari NIK / Nama"
            value="{{ request('keyword') }}"
        >
        <button type="submit">Cari</button>
        <a href="{{ route('master.employees') }}">Reset</a>
    </form>

    <!-- 📋 TABLE -->
    <table>
        <thead>
            <tr>
                <th width="180">NIK</th>
                <th>Nama</th>
                <th width="120">Status</th>
                <th width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $emp)
                <tr>
                    <td>{{ $emp->nik }}</td>
                    <td>{{ $emp->nama }}</td>
                    <td>{{ $emp->status ?? 'aktif' }}</td>
                    <td>
                        <a href="{{ route('master.employees.edit', $emp->id) }}"
                         style="padding:6px 10px; background:#f59e0b; color:white; border-radius:6px; text-decoration:none; font-size:13px">
                         Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty">
                        Tidak ada data karyawan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $employees->links('vendor.pagination.simple') }}
    </div>

</div>
@endsection
