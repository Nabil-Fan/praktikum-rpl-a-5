<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoEats — Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            background: #ece8df;
            color: var(--charcoal);
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        header {
            background: var(--charcoal);
            height: 56px;
            display: flex; align-items: center;
            padding: 0 2rem;
            gap: 1.5rem;
            position: sticky; top: 0; z-index: 100;
        }
        .header-brand { font-family: var(--font-d); font-size: 1.2rem; color: var(--cornsilk); }
        .header-sep { width: 1px; height: 20px; background: rgba(254,250,224,.15); }
        .header-label {
            font-size: .7rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--camel);
        }
        .header-right { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
        .header-admin { font-size: .82rem; color: rgba(254,250,224,.7); }
        .btn-logout {
            padding: .35rem .85rem;
            background: transparent;
            border: 1.5px solid rgba(254,250,224,.2);
            color: rgba(254,250,224,.6);
            border-radius: 999px;
            font-family: var(--font-b); font-size: .75rem; font-weight: 600;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-logout:hover { border-color: rgba(254,250,224,.5); color: var(--cornsilk); }

        /* ── LAYOUT ── */
        .layout { display: flex; min-height: calc(100vh - 56px); }

        /* ── SIDENAV ── */
        .sidenav {
            width: 200px;
            background: white;
            border-right: 1px solid rgba(56,44,35,.08);
            padding: 1.5rem 0;
            position: sticky;
            top: 56px;
            height: calc(100vh - 56px);
            overflow-y: auto;
        }
        .sidenav-label {
            font-size: .62rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: rgba(56,44,35,.3);
            padding: .5rem 1.25rem .25rem;
        }
        .sidenav-item {
            display: flex; align-items: center; gap: .65rem;
            padding: .55rem 1.25rem;
            font-size: .82rem;
            color: rgba(56,44,35,.55);
            text-decoration: none;
            border-left: 2px solid transparent;
            transition: all .15s;
        }
        .sidenav-item:hover { color: var(--charcoal); background: rgba(56,44,35,.03); }
        .sidenav-item.active {
            color: var(--charcoal);
            border-left-color: var(--olive);
            background: rgba(95,111,82,.06);
            font-weight: 600;
        }

        /* ── CONTENT ── */
        .content { flex: 1; padding: 2rem 2rem 3rem; overflow-x: auto; }

        /* Page header */
        .page-header { margin-bottom: 1.75rem; }
        .page-header h1 { font-family: var(--font-d); font-size: 1.5rem; color: var(--charcoal); }
        .breadcrumb { font-size: .75rem; color: var(--camel); margin-top: .25rem; }

        /* Summary cards */
        .summary-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .sum-card {
            background: white;
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            box-shadow: 0 1px 4px rgba(56,44,35,.07);
            display: flex; align-items: center; gap: .85rem;
        }
        .sum-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .si-olive   { background: rgba(95,111,82,.12); }
        .si-camel   { background: rgba(185,148,112,.12); }
        .si-laurel  { background: rgba(169,179,136,.15); }
        .si-red     { background: rgba(180,60,60,.08); }
        .sum-label { font-size: .7rem; color: var(--camel); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
        .sum-value { font-family: var(--font-d); font-size: 1.5rem; color: var(--charcoal); line-height: 1.1; }

        /* Table panel */
        .panel {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(56,44,35,.07);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid rgba(56,44,35,.07);
        }
        .panel-title { font-family: var(--font-d); font-size: 1.05rem; color: var(--charcoal); }
        .tab-row { display: flex; gap: .25rem; }
        .tab {
            padding: .3rem .75rem;
            border-radius: 999px;
            font-size: .75rem; font-weight: 600;
            border: 1.5px solid transparent;
            background: transparent;
            color: var(--camel);
            cursor: pointer; transition: all .15s;
        }
        .tab.active { background: var(--olive); color: white; border-color: var(--olive); }
        .tab:not(.active):hover { border-color: var(--laurel); color: var(--olive); }

        /* Data table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: rgba(56,44,35,.03); }
        th {
            text-align: left;
            padding: .65rem 1.25rem;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: rgba(56,44,35,.4);
            white-space: nowrap;
        }
        td {
            padding: .85rem 1.25rem;
            font-size: .83rem;
            color: var(--charcoal);
            border-top: 1px solid rgba(56,44,35,.05);
        }
        tr:hover td { background: rgba(56,44,35,.015); }

        /* Badge */
        .badge {
            display: inline-block;
            padding: .2rem .65rem;
            border-radius: 999px;
            font-size: .68rem; font-weight: 700;
        }
        .b-pending  { background: rgba(185,148,112,.12); color: #7a4e20; }
        .b-approved { background: rgba(95,111,82,.12);   color: var(--olive); }
        .b-rejected { background: rgba(180,60,60,.08);   color: #a03030; }

        /* Action buttons */
        .btn-approve {
            padding: .3rem .75rem;
            background: var(--olive); color: white;
            border: none; border-radius: 6px;
            font-family: var(--font-b); font-size: .75rem; font-weight: 600;
            cursor: pointer; transition: background .15s;
            margin-right: .3rem;
        }
        .btn-approve:hover { background: var(--charcoal); }
        .btn-reject {
            padding: .3rem .75rem;
            background: transparent; color: #a03030;
            border: 1.5px solid rgba(180,60,60,.25);
            border-radius: 6px;
            font-family: var(--font-b); font-size: .75rem; font-weight: 600;
            cursor: pointer; transition: all .15s;
        }
        .btn-reject:hover { background: rgba(180,60,60,.06); }

        /* Empty state */
        .empty-table {
            text-align: center;
            padding: 3rem;
            color: var(--camel);
        }
        .empty-table .ei { font-size: 2rem; margin-bottom: .75rem; opacity: .4; }
        .empty-table p { font-size: .83rem; }

        /* Two col */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    </style>
</head>
<body>

<header>
    <div class="header-brand">🌿 EcoEats</div>
    <div class="header-sep"></div>
    <div class="header-label">Admin Panel</div>
    <div class="header-right">
        <span class="header-admin">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">Keluar</button>
        </form>
    </div>
</header>

<div class="layout">

    <!-- SIDENAV -->
    <aside class="sidenav">
        <div class="sidenav-label">Overview</div>
        <a href="#" class="sidenav-item active">📊 Dashboard</a>

        <div class="sidenav-label">Manajemen</div>
        <a href="#" class="sidenav-item">🏪 Verifikasi Merchant</a>
        <a href="#" class="sidenav-item">👥 Pengguna</a>
        <a href="#" class="sidenav-item">🍱 Food Listing</a>
        <a href="#" class="sidenav-item">📋 Semua Pesanan</a>

        <div class="sidenav-label">Sistem</div>
        <a href="#" class="sidenav-item">🏷 Kategori</a>
        <a href="#" class="sidenav-item">⚙️ Pengaturan</a>
    </aside>

    <!-- CONTENT -->
    <main class="content">

        <div class="page-header">
            <h1>Dashboard Admin</h1>
            <div class="breadcrumb">EcoEats / Admin / Dashboard</div>
        </div>

        <div class="summary-row">
            <div class="sum-card">
                <div class="sum-icon si-camel">⏳</div>
                <div>
                    <div class="sum-label">Pending Verifikasi</div>
                    <div class="sum-value">—</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon si-olive">✅</div>
                <div>
                    <div class="sum-label">Merchant Aktif</div>
                    <div class="sum-value">—</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon si-laurel">👥</div>
                <div>
                    <div class="sum-label">Total User</div>
                    <div class="sum-value">—</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon si-red">🍱</div>
                <div>
                    <div class="sum-label">Listing Aktif</div>
                    <div class="sum-value">—</div>
                </div>
            </div>
        </div>

        <!-- Verifikasi Merchant -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Pengajuan Verifikasi Merchant</div>
                <div class="tab-row">
                    <button class="tab active">Semua</button>
                    <button class="tab">Pending</button>
                    <button class="tab">Disetujui</button>
                    <button class="tab">Ditolak</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Usaha</th>
                        <th>Pemilik</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6">
                            <div class="empty-table">
                                <div class="ei">📄</div>
                                <p>Belum ada pengajuan verifikasi merchant.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="two-col">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Pengguna Terbaru</div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3">
                                <div class="empty-table">
                                    <div class="ei">👥</div>
                                    <p>Belum ada data pengguna.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Pesanan Terkini</div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Merchant</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3">
                                <div class="empty-table">
                                    <div class="ei">📋</div>
                                    <p>Belum ada pesanan.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

</body>
</html>