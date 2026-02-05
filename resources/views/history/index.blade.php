@extends('layouts.app')

@section('title', 'History Peminjaman PDA')

@section('content')
<style>
    .card {
        background: #ffffff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }

    h2 {
        margin: 0 0 20px 0;
        font-size: 22px;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* FILTER */
    .filter {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 10px;
        margin-bottom: 18px;
    }

    .filter input {
        padding: 9px 10px;
        font-size: 14px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
    }

    .btn {
        border: none;
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-search {
        background: #2563eb;
        color: #fff;
    }

    .btn-reset {
        background: #e5e7eb;
        color: #111827;
        text-decoration: none;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn-export {
        background: #16a34a;
        color: #fff;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 14px;
        text-decoration: none;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }

    th, td {
        border: 1px solid #e5e7eb;
        padding: 10px;
        text-align: center;
    }

    th {
        background: #f1f5f9;
        font-weight: 600;
        color: #111827;
    }

    tr:nth-child(even) {
        background: #f9fafb;
    }

    .status-returned {
        color: #16a34a;
        font-weight: bold;
    }

    .status-overdue {
        color: #dc2626;
        font-weight: bold;
    }

    .empty {
        text-align: center;
        padding: 40px;
        color: #6b7280;
        font-size: 15px;
    }

    .pagination {
        margin-top: 18px;
    }
</style>

<div class="card">

    <h2>📜 History Peminjaman PDA</h2>

    <!-- FILTER -->
    <form method="GET">
        <div class="filter">
            <input type="text" name="nik" placeholder="NIK" value="{{ request('nik') }}">
            <input type="text" name="pda_no" placeholder="PDA NO" value="{{ request('pda_no') }}">
            <input type="date" name="from" value="{{ request('from') }}">
            <input type="date" name="to" value="{{ request('to') }}">

            <button class="btn btn-search">Cari</button>
            <a href="/history" class="btn btn-reset">Bersihkan</a>
        </div>
    </form>

    @if($histories->count())
        <div class="toolbar">
            <div></div>

            <a
                class="btn-export"
                href="/history/export?nik={{ request('nik') }}&pda_no={{ request('pda_no') }}&from={{ request('from') }}&to={{ request('to') }}"
            >
                ⬇ Export Excel
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>PDA</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Waktu Pinjam</th>
                    <th>Waktu Kembali</th>
                    <th>Durasi (Jam)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $h)
                    <tr>
                        <td>{{ $h->pda->pda_no }}</td>
                        <td>{{ $h->employee->nik }}</td>
                        <td>{{ $h->employee->nama }}</td>
                        <td>{{ $h->borrowed_at }}</td>
                        <td>{{ $h->returned_at ?? '-' }}</td>
                        <td>
                            {{ $h->duration_minutes ? round($h->duration_minutes / 60, 1) : '-' }}
                        </td>
                        <td class="{{ $h->status === 'overdue' ? 'status-overdue' : 'status-returned' }}">
                            {{ strtoupper($h->status) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $histories->links() }}
        </div>
    @else
        <div class="empty">
            🔍 Silakan lakukan pencarian history peminjaman
        </div>
    @endif

</div>
@endsection
