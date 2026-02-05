<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'PDA System')</title>

    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f6f8;
        }

        /* HEADER */
        .header {
            background: #111827;
            color: #fff;
            padding: 14px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .brand {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 14px;
        }

        .role {
            background: #2563eb;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .home-btn {
            background: #10b981;
            color: #fff;
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
        }

        .home-btn:hover {
            background: #059669;
        }

        .logout-btn {
            background: #dc2626;
            border: none;
            color: #fff;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        .content {
            padding: 30px;
        }
    </style>
</head>
<body>

<!-- HEADER GLOBAL -->
<div class="header">
    <div class="brand">📦 PDA System</div>

    @auth
    <div class="user-info">
        <div>{{ auth()->user()->name }}</div>
        <div class="role">{{ auth()->user()->role }}</div>

        {{-- HOME BUTTON (ADMIN ONLY & BUKAN DI /home) --}}
        @if(auth()->user()->role === 'admin' && request()->path() !== 'home')
            <a href="/home" class="home-btn">🏠 Home</a>
        @endif

        <form method="POST" action="/logout">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>
    @endauth
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>
