@extends('layouts.app')

@section('title', 'Admin Home - PDA System')

@section('content')

<style>
    .container {
        max-width: 900px;
        margin: 80px auto;
        padding: 0 20px;
    }

    .card {
        background: #ffffff;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
    }

    .header h2 {
        margin: 0;
        font-size: 22px;
        color: #111827;
    }

    .welcome {
        color: #6b7280;
        font-size: 14px;
        margin-top: 4px;
    }

    .menu {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-top: 25px;
    }

    .menu a {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        border-radius: 12px;
        text-decoration: none;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        color: #111827;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .menu a:hover {
        background: #eef2ff;
        border-color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(99,102,241,0.15);
    }

    .icon {
        font-size: 22px;
    }

    footer {
        margin-top: 40px;
        text-align: center;
        font-size: 12px;
        color: #9ca3af;
    }
</style>

<div class="container">
    <div class="card">

        <div class="header">
            <div>
                <h2>🏠 Admin Home</h2>
                <div class="welcome">
                    Selamat datang, <strong>{{ auth()->user()->name }}</strong>
                </div>
            </div>
        </div>

        <div class="menu">
            <a href="/">
                <span class="icon">📜</span>
                <span>History Peminjaman</span>
            </a>

            <a href="/dashboard-summary">
                <span class="icon">📊</span>
                <span>Summary Dashboard</span>
            </a>

            <a href="/dashboard-tv">
                <span class="icon">📺</span>
                <span>Dashboard TV</span>
            </a>

            <a href="/audit-log">
                <span class="icon">🔐</span>
                <span>Audit Log</span>
            </a>

            <a href="/operator">
                <span class="icon">🧑‍💼</span>
                <span>Halaman Operator</span>
            </a>

            {{-- ✅ MENU BARU: MASTER DATA --}}
            <a href="{{ route('master.index') }}">
                <span class="icon">🗂️</span>
                <span>Master Data</span>
            </a>
        </div>

    </div>

    <footer>
        PDA System • Internal Use Only
    </footer>
</div>

@endsection
