@extends('layouts.app')

@section('title', 'Summary Dashboard PDA')

@section('content')

<style>
    body {
        font-family: Arial;
        background:#f4f6f8;
    }

    table {
        width:100%;
        border-collapse: collapse;
        background:white;
        margin-bottom:30px;
    }

    th, td {
        padding:10px;
        border:1px solid #ddd;
        text-align:center;
    }

    th {
        background:#e5e7eb;
    }

    /* === HOVER TOOLTIP === */
    .hover-box {
        position: relative;
        cursor: pointer;
        color:#2563eb;
        font-weight:bold;
    }

    .tooltip {
        display:none;
        position:absolute;
        top:120%;
        left:50%;
        transform:translateX(-50%);
        background:#111827;
        color:white;
        padding:10px;
        border-radius:8px;
        font-size:12px;
        width:260px;
        z-index:99;
        text-align:left;
    }

    .hover-box:hover .tooltip {
        display:block;
    }

    .tooltip div {
        border-bottom:1px solid #374151;
        padding:4px 0;
    }

    .tooltip div:last-child {
        border-bottom:none;
    }
</style>

<h1>📊 Summary Dashboard PDA</h1>

<h2>👤 Pemakaian per Karyawan</h2>
<table>
    <tr>
        <th>NIK</th>
        <th>Nama</th>
        <th>Total Pinjam</th>
        <th>Total Jam</th>
    </tr>

    @foreach($perEmployee as $row)
    <tr>
        <td>{{ $row['employee']->nik }}</td>
        <td class="hover-box">
            {{ $row['employee']->nama }}

            <div class="tooltip">
                @foreach($row['details'] as $d)
                    <div>
                        📦 {{ $d->pda->pda_no }}<br>
                        ⏱ {{ $d->borrowed_at }}
                    </div>
                @endforeach
            </div>
        </td>
        <td>{{ $row['total_pinjam'] }}</td>
        <td>{{ $row['total_jam'] }}</td>
    </tr>
    @endforeach
</table>

<h2>📦 PDA Paling Sering Dipakai</h2>
<table>
    <tr>
        <th>PDA</th>
        <th>Total Pinjam</th>
        <th>Total Jam</th>
    </tr>

    @foreach($perPda as $row)
    <tr>
        <td class="hover-box">
            {{ $row['pda']->pda_no }}

            <div class="tooltip">
                @foreach($row['details'] as $d)
                    <div>
                        👤 {{ $d->employee->nama }}<br>
                        ⏱ {{ $d->borrowed_at }}
                    </div>
                @endforeach
            </div>
        </td>
        <td>{{ $row['total_pinjam'] }}</td>
        <td>{{ $row['total_jam'] }}</td>
    </tr>
    @endforeach
</table>

@endsection
