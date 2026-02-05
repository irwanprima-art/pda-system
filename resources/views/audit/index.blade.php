@extends('layouts.app')

@section('title','Audit Log')

@section('content')

<style>
    body { font-family: Arial, sans-serif; background:#f4f6f8; }
    .container {
        max-width: 1200px;
        margin: auto;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .btn-refresh {
        background:#2563eb;
        color:#fff;
        padding:8px 14px;
        border-radius:6px;
        text-decoration:none;
        font-size:14px;
    }

    table { width:100%; border-collapse:collapse; }
    th, td { border:1px solid #e5e7eb; padding:8px; font-size:14px; }
    th { background:#f1f5f9; }
    tr:nth-child(even) { background:#f9fafb; }

    .action-borrow { color:#2563eb; font-weight:bold; }
    .action-return { color:#16a34a; font-weight:bold; }

    .empty { text-align:center; padding:30px; color:#6b7280; }
</style>

<div class="container">
    <div class="header">
        <h2>🔐 Audit Log (Internal)</h2>
        <a href="{{ url()->current() }}" class="btn-refresh">🔄 Refresh</a>
    </div>

    {{-- ISI LAMA TIDAK DIUBAH --}}
</div>

@endsection
