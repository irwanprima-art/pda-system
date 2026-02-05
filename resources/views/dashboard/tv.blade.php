<!DOCTYPE html>
<html>
<head>
    <title>Dashboard PDA - TV</title>

    <style>
        body {
            margin:0;
            font-family: Arial, sans-serif;
            background:#0f172a;
            color:white;
        }

        .container {
            padding:20px;
        }

        .header {
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .header h1 {
            margin:0;
            font-size:28px;
        }

        .btn-refresh {
            background:#2563eb;
            color:white;
            border:none;
            padding:10px 18px;
            font-size:16px;
            border-radius:6px;
            cursor:pointer;
        }

        .btn-refresh:disabled {
            background:#475569;
            cursor:not-allowed;
        }

        .summary {
            display:grid;
            grid-template-columns: repeat(4, 1fr);
            gap:15px;
            margin-bottom:20px;
        }

        .card {
            padding:20px;
            border-radius:10px;
            text-align:center;
            font-size:22px;
        }

        .total { background:#334155; }
        .available { background:#16a34a; }
        .borrowed { background:#2563eb; }
        .overdue { background:#dc2626; }

        table {
            width:100%;
            border-collapse: collapse;
            font-size:18px;
        }

        th, td {
            padding:10px;
            text-align:center;
        }

        th {
            background:#1e293b;
        }

        tr:nth-child(even) {
            background:#020617;
        }

        .status-available { color:#22c55e; font-weight:bold; }
        .status-borrowed { color:#60a5fa; font-weight:bold; }
        .status-overdue { color:#f87171; font-weight:bold; }
    </style>
</head>

<body>
<div class="container">

    <!-- HEADER -->
    <div class="header">
        <h1>📺 DASHBOARD STATUS PDA</h1>

        <button
            id="btnRefresh"
            class="btn-refresh"
            onclick="refreshDashboard()"
        >
            🔄 Refresh
        </button>
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <div class="card total">
            Total PDA<br><b>{{ $total }}</b>
        </div>
        <div class="card available">
            Available<br><b>{{ $available }}</b>
        </div>
        <div class="card borrowed">
            Borrowed<br><b>{{ $borrowed }}</b>
        </div>
        <div class="card overdue">
            Overdue<br><b>{{ $overdue }}</b>
        </div>
    </div>

    <!-- TABLE -->
    <table>
        <tr>
            <th>PDA</th>
            <th>Status</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Dipinjam Sejak</th>
            <th>Durasi (Jam)</th>
        </tr>

        @foreach($pdas as $pda)
            @php
                $trx = $pda->activeTransaction;
                $hours = $trx ? \Carbon\Carbon::parse($trx->borrowed_at)->diffInMinutes(now()) / 60 : null;
                $status = $trx && $hours > 9 ? 'overdue' : $pda->status;
            @endphp

            <tr>
                <td>{{ $pda->pda_no }}</td>

                <td class="status-{{ $status }}">
                    {{ strtoupper($status) }}
                </td>

                <td>{{ $trx->employee->nik ?? '-' }}</td>
                <td>{{ $trx->employee->nama ?? '-' }}</td>
                <td>{{ $trx->borrowed_at ?? '-' }}</td>
                <td>{{ $hours ? round($hours,1) : '-' }}</td>
            </tr>
        @endforeach
    </table>

</div>

<!-- SCRIPT -->
<script>
function refreshDashboard() {
    const btn = document.getElementById('btnRefresh');
    btn.innerText = '⏳ Refreshing...';
    btn.disabled = true;
    location.reload();
}
</script>

</body>
</html>
