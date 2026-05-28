<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoEats — Portal Merchant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --olive:     #5F6F52;
            --laurel:    #A9B388;
            --cornsilk:  #FEFAE0;
            --camel:     #B99470;
            --charcoal:  #382C23;
            --font-d:    'Fraunces', serif;
            --font-b:    'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-b);
            background: #f5f0e8;
            color: var(--charcoal);
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--charcoal);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 50;
        }
        .sidebar-brand {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(254,250,224,.08);
        }
        .sidebar-brand-name {
            font-family: var(--font-d);
            font-size: 1.3rem;
            color: var(--cornsilk);
        }
        .sidebar-role {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--camel);
            margin-top: .2rem;
        }

        .sidebar-user {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(254,250,224,.08);
        }
        .sidebar-avatar {
            width: 38px; height: 38px;
            background: var(--camel);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            margin-bottom: .5rem;
        }
        .sidebar-name { font-size: .85rem; font-weight: 600; color: var(--cornsilk); }
        .sidebar-status {
            display: inline-flex; align-items: center; gap: .3rem;
            font-size: .68rem; color: var(--laurel);
            margin-top: .2rem;
        }
        .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--laurel); }

        nav.sidebar-nav {
            flex: 1;
            padding: 1rem 0;
        }
        .nav-section-label {
            font-size: .63rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(254,250,224,.3);
            padding: .75rem 1.5rem .3rem;
        }
        .nav-item {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem 1.5rem;
            font-size: .83rem;
            color: rgba(254,250,224,.6);
            text-decoration: none;
            border-left: 2.5px solid transparent;
            transition: color .15s, border-color .15s, background .15s;
            cursor: pointer;
        }
        .nav-item:hover { color: var(--cornsilk); background: rgba(254,250,224,.04); }
        .nav-item.active {
            color: var(--cornsilk);
            border-left-color: var(--camel);
            background: rgba(185,148,112,.1);
        }
        .nav-icon { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(254,250,224,.08);
        }
        .btn-logout {
            width: 100%;
            padding: .6rem;
            background: transparent;
            border: 1.5px solid rgba(254,250,224,.15);
            color: rgba(254,250,224,.5);
            border-radius: 8px;
            font-family: var(--font-b);
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            transition: border-color .2s, color .2s;
        }
        .btn-logout:hover { border-color: rgba(254,250,224,.4); color: var(--cornsilk); }

        /* ── MAIN ── */
        .main {
            margin-left: 240px;
            flex: 1;
            padding: 2rem 2.5rem;
            min-height: 100vh;
        }

        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem;
        }
        .page-title { font-family: var(--font-d); font-size: 1.6rem; color: var(--charcoal); }
        .page-sub { font-size: .83rem; color: var(--camel); margin-top: .2rem; }

        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .65rem 1.25rem;
            background: var(--camel);
            color: white;
            border: none; border-radius: 10px;
            font-family: var(--font-b); font-weight: 700; font-size: .85rem;
            cursor: pointer;
            transition: background .2s;
            text-decoration: none;
        }
        .btn-primary:hover { background: var(--charcoal); }

        /* Stats grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 6px rgba(56,44,35,.06);
            border-top: 3px solid transparent;
        }
        .stat-card.s1 { border-top-color: var(--olive); }
        .stat-card.s2 { border-top-color: var(--camel); }
        .stat-card.s3 { border-top-color: var(--laurel); }
        .stat-card.s4 { border-top-color: #e8a87c; }
        .stat-label { font-size: .72rem; color: var(--camel); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .4rem; }
        .stat-value { font-family: var(--font-d); font-size: 1.8rem; color: var(--charcoal); }
        .stat-note { font-size: .72rem; color: var(--laurel); margin-top: .3rem; }

        /* Two-col layout */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

        /* Panel */
        .panel {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 6px rgba(56,44,35,.06);
        }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .panel-title { font-family: var(--font-d); font-size: 1.1rem; color: var(--charcoal); }
        .panel-link { font-size: .78rem; color: var(--camel); text-decoration: none; font-weight: 600; }

        /* Status badge */
        .badge {
            display: inline-block;
            padding: .2rem .6rem;
            border-radius: 999px;
            font-size: .68rem; font-weight: 700;
        }
        .badge-pending   { background: rgba(185,148,112,.12); color: #8a5c2a; }
        .badge-confirmed { background: rgba(95,111,82,.12);   color: var(--olive); }
        .badge-ready     { background: rgba(169,179,136,.2);  color: #3d5c2a; }
        .badge-approved  { background: rgba(95,111,82,.12);   color: var(--olive); }
        .badge-rejected  { background: rgba(180,60,60,.1);    color: #a03030; }

        /* Verification notice */
        .notice {
            background: rgba(185,148,112,.1);
            border: 1.5px solid rgba(185,148,112,.3);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex; align-items: flex-start; gap: .75rem;
            margin-bottom: 2rem;
        }
        .notice-icon { font-size: 1.2rem; flex-shrink: 0; }
        .notice-title { font-size: .85rem; font-weight: 700; color: var(--charcoal); margin-bottom: .2rem; }
        .notice-text { font-size: .8rem; color: var(--camel); line-height: 1.5; }

        /* Empty */
        .empty-panel {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--camel);
        }
        .empty-panel .empty-icon { font-size: 2rem; margin-bottom: .75rem; opacity: .5; }
        .empty-panel p { font-size: .83rem; line-height: 1.6; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-name">🌿 EcoEats</div>
        <div class="sidebar-role">Portal Merchant</div>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar">🏪</div>
        <div class="sidebar-name">{{ auth()->user()->name }}</div>
        <div class="sidebar-status"><span class="dot"></span> Aktif</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="#" class="nav-item active">
            <span class="nav-icon">📊</span> Dashboard
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">🍱</span> Menu Surplus
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">📋</span> Pesanan Masuk
        </a>

        <div class="nav-section-label">Akun</div>
        <a href="#" class="nav-item">
            <span class="nav-icon">🏪</span> Profil Usaha
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">📄</span> Dokumen Verifikasi
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">↩ Keluar</button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<main class="main">
    <div class="topbar">
        <div>
            <div class="page-title">Selamat datang kembali 👋</div>
            <div class="page-sub">Pantau aktivitas usaha Anda hari ini.</div>
        </div>
        <a href="#" class="btn-primary">＋ Tambah Menu Surplus</a>
    </div>

    {{-- Notifikasi jika belum verified --}}
    @php $status = auth()->user()->merchantProfile?->verification_status ?? 'pending'; @endphp
    @if($status !== 'approved')
    <div class="notice">
        <span class="notice-icon">⏳</span>
        <div>
            <div class="notice-title">Akun Anda sedang dalam proses verifikasi</div>
            <div class="notice-text">Dokumen usaha Anda sedang ditinjau oleh tim EcoEats. Anda belum dapat mempublikasikan menu surplus hingga verifikasi disetujui.</div>
        </div>
    </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card s1">
            <div class="stat-label">Menu Aktif</div>
            <div class="stat-value">—</div>
            <div class="stat-note">listing tersedia</div>
        </div>
        <div class="stat-card s2">
            <div class="stat-label">Pesanan Masuk</div>
            <div class="stat-value">—</div>
            <div class="stat-note">menunggu konfirmasi</div>
        </div>
        <div class="stat-card s3">
            <div class="stat-label">Selesai Hari Ini</div>
            <div class="stat-value">—</div>
            <div class="stat-note">pickup berhasil</div>
        </div>
        <div class="stat-card s4">
            <div class="stat-label">Pendapatan</div>
            <div class="stat-value">—</div>
            <div class="stat-note">bulan ini</div>
        </div>
    </div>

    <div class="two-col">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Pesanan Terbaru</div>
                <a href="#" class="panel-link">Lihat semua →</a>
            </div>
            <div class="empty-panel">
                <div class="empty-icon">📋</div>
                <p>Belum ada pesanan masuk.<br>Tambahkan menu surplus untuk mulai menerima pesanan.</p>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Menu Surplus Aktif</div>
                <a href="#" class="panel-link">Kelola →</a>
            </div>
            <div class="empty-panel">
                <div class="empty-icon">🍱</div>
                <p>Belum ada menu surplus.<br>Klik "Tambah Menu Surplus" untuk memulai.</p>
            </div>
        </div>
    </div>
</main>

</body>
</html>