<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Peminjaman PDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; background:#f4f6f8; margin:0; }

        /* =====================
           HEADER GLOBAL
        ===================== */
        .header {
            background:#111827;
            color:#fff;
            padding:14px 26px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:0 4px 10px rgba(0,0,0,0.25);
        }

        .brand {
            font-size:18px;
            font-weight:bold;
        }

        .user-info {
            display:flex;
            align-items:center;
            gap:14px;
            font-size:14px;
        }

        .role {
            background:#2563eb;
            padding:4px 10px;
            border-radius:20px;
            font-size:12px;
            text-transform:uppercase;
        }

        .logout-btn {
            background:#dc2626;
            border:none;
            color:#fff;
            padding:6px 14px;
            border-radius:6px;
            cursor:pointer;
            font-size:13px;
        }

        .logout-btn:hover {
            background:#b91c1c;
        }

        /* =====================
           KONTEN
        ===================== */
        .container {
            max-width: 960px;
            margin: 32px auto;
            background:#ffffff;
            padding:26px 28px;
            border-radius:14px;
            box-shadow:0 12px 30px rgba(0,0,0,0.08);
        }

        h2 { margin:0 0 18px; }

        .row {
            display:flex;
            gap:12px;
            margin-bottom:14px;
            flex-wrap:wrap;
        }

        input, button {
            padding:10px;
            font-size:16px;
        }

        input[disabled] { background:#eee; }

        button { cursor:pointer; }

        table {
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        th, td {
            border:1px solid #ddd;
            padding:8px;
            text-align:center;
        }

        th { background:#eee; }

        .overdue {
            background:#ffdddd;
            color:#a00;
            font-weight:bold;
        }

        .available { color:green; }
        .borrowed { color:orange; }

        .msg { margin-top:10px; font-weight:bold; }
        .success { color:green; }
        .error { color:red; }
    </style>
</head>
<body>

<!-- =====================
     HEADER
===================== -->
<div class="header">
    <div class="brand">📦 PDA System</div>

    <div class="user-info">
        <div>{{ auth()->user()->name ?? 'Operator' }}</div>
        <div class="role">{{ auth()->user()->role ?? 'operator' }}</div>

        <form method="POST" action="/logout">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<!-- =====================
     KONTEN
===================== -->
<div class="container">
    <h2>📦 Sistem Peminjaman PDA</h2>

    <div class="row">
        <input type="text" id="nik" placeholder="Scan / Input NIK">
        <input type="text" id="pda_no" placeholder="Scan / Input PDA" disabled>
        <button onclick="borrow()">PINJAM</button>
        <button onclick="returnPda()">KEMBALI</button>
    </div>

    <div id="message" class="msg"></div>

    <h3>Status PDA</h3>
    <table>
        <thead>
            <tr>
                <th>PDA</th>
                <th>Status</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Waktu Pinjam</th>
                <th>Durasi (jam)</th>
            </tr>
        </thead>
        <tbody id="statusBody"></tbody>
    </table>
</div>

<!-- =====================
     SCRIPT (UTUH + AUTO CLEAR)
===================== -->
<script>
const API = '/api/pda';

const nikInput = document.getElementById('nik');
const pdaInput = document.getElementById('pda_no');
const messageEl = document.getElementById('message');

/* =====================
   SOUND BEEP
===================== */
function beep(success = true) {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.type = 'sine';
    osc.frequency.value = success ? 800 : 300;
    gain.gain.value = 0.2;

    osc.start();
    setTimeout(() => {
        osc.stop();
        ctx.close();
    }, success ? 150 : 400);
}

/* =====================
   MESSAGE
===================== */
function showMessage(msg, isError = false) {
    messageEl.innerText = msg;
    messageEl.className = 'msg ' + (isError ? 'error' : 'success');
    beep(!isError);
}

/* =====================
   AUTO CLEAR (TAMBAHAN)
===================== */
function clearInput() {
    nikInput.value = '';
    pdaInput.value = '';
    pdaInput.disabled = true;
    nikInput.focus();
}

/* =====================
   INPUT FLOW
===================== */
nikInput.addEventListener('input', () => {
    if (nikInput.value.trim() !== '') {
        pdaInput.disabled = false;
    } else {
        pdaInput.disabled = true;
        pdaInput.value = '';
    }
});

nikInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') pdaInput.focus();
});

pdaInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') borrow();
});

/* =====================
   API ACTIONS
===================== */
function borrow() {
    fetch(API + '/borrow', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            nik: nikInput.value,
            pda_no: pdaInput.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            const isError =
                data.message.includes('belum dikembalikan') ||
                data.message.includes('dipinjam');

            showMessage(data.message, isError);

            if (!isError) {
                clearInput(); // ✅ AUTO CLEAR
            }
        }
        loadStatus();
    });
}

function returnPda() {
    fetch(API + '/return', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            pda_no: pdaInput.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            showMessage(data.message, false);
            clearInput(); // ✅ AUTO CLEAR
        }
        loadStatus();
    });
}

/* =====================
   LOAD STATUS
===================== */
function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function loadStatus() {
    fetch(API + '/status')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('statusBody');
            tbody.innerHTML = '';

            data.forEach(r => {
                const tr = document.createElement('tr');
                if (r.overdue) tr.className = 'overdue';

                tr.innerHTML = `
                    <td>${r.pda_no}</td>
                    <td class="${r.status}">${r.status}</td>
                    <td>${r.nik ?? '-'}</td>
                    <td>${r.nama ?? '-'}</td>
                    <td>${formatDate(r.borrowed_at)}</td>
                    <td>${r.duration_hours ?? '-'}</td>
                `;
                tbody.appendChild(tr);
            });
        });
}

loadStatus();
</script>

</body>
</html>
