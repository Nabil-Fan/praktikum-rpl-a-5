{{--
    resources/views/dashboard/admin.blade.php
    Data dari: App\Http\Controllers\Admin\DashboardController@index
    Variabel: $stats (array), $pendingMerchants (Collection), $recentUsers (Collection)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — EcoEats Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --olive:    #5F6F52;
            --laurel:   #A9B388;
            --cornsilk: #FEFAE0;
            --camel:    #B99470;
            --charcoal: #382C23;
            --bg:       #eee9df;
            --white:    #ffffff;
            --font-d:   'Fraunces', serif;
            --font-b:   'Plus Jakarta Sans', sans-serif;
            --radius:   14px;
            --shadow:   0 1px 3px rgba(56,44,35,.06), 0 4px 16px rgba(56,44,35,.07);
        }

        body { font-family: var(--font-b); background: var(--bg); color: var(--charcoal); min-height: 100vh; }

        /* ── TOPBAR ── */
        header {
            background: var(--charcoal);
            height: 58px;
            display: flex; align-items: center;
            padding: 0 1.75rem;
            gap: 1.25rem;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 12px rgba(0,0,0,.2);
        }
        .h-brand { font-family: var(--font-d); font-size: 1.15rem; color: var(--cornsilk); display: flex; align-items: center; gap: .4rem; }
        .h-sep { width: 1px; height: 18px; background: rgba(254,250,224,.12); }
        .h-label { font-size: .68rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--camel); }
        .h-right { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
        .h-user { font-size: .8rem; color: rgba(254,250,224,.65); }
        .h-user strong { color: var(--cornsilk); font-weight: 600; }
        .btn-out {
            padding: .32rem .8rem;
            background: transparent; border: 1.5px solid rgba(254,250,224,.18);
            color: rgba(254,250,224,.55); border-radius: 999px;
            font-family: var(--font-b); font-size: .73rem; font-weight: 600;
            cursor: pointer; transition: all .2s;
        }
        .btn-out:hover { border-color: rgba(254,250,224,.45); color: var(--cornsilk); }

        /* ── LAYOUT ── */
        .layout { display: flex; min-height: calc(100vh - 58px); }

        /* ── SIDENAV ── */
        .sidenav {
            width: 210px; background: var(--white);
            border-right: 1px solid rgba(56,44,35,.07);
            padding: 1.25rem 0;
            position: sticky; top: 58px;
            height: calc(100vh - 58px); overflow-y: auto;
            flex-shrink: 0;
        }
        .sn-label {
            font-size: .6rem; font-weight: 700; letter-spacing: .13em;
            text-transform: uppercase; color: rgba(56,44,35,.28);
            padding: .65rem 1.25rem .2rem;
        }
        .sn-item {
            display: flex; align-items: center; gap: .6rem;
            padding: .52rem 1.25rem;
            font-size: .8rem; color: rgba(56,44,35,.5);
            text-decoration: none;
            border-left: 2.5px solid transparent;
            transition: all .15s;
        }
        .sn-item:hover { color: var(--charcoal); background: rgba(56,44,35,.025); }
        .sn-item.active { color: var(--charcoal); border-left-color: var(--olive); background: rgba(95,111,82,.06); font-weight: 600; }
        .sn-icon { width: 18px; text-align: center; font-size: .95rem; }

        /* ── CONTENT ── */
        .content { flex: 1; padding: 2rem 2.25rem 4rem; overflow-x: hidden; }

        /* Page header */
        .pg-head { margin-bottom: 1.75rem; }
        .pg-head h1 { font-family: var(--font-d); font-size: 1.55rem; font-weight: 700; color: var(--charcoal); }
        .pg-crumb { font-size: .73rem; color: var(--camel); margin-top: .2rem; }

        /* Flash messages */
        .flash {
            padding: .75rem 1rem; border-radius: 10px;
            font-size: .83rem; margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .flash-success { background: rgba(95,111,82,.1); border: 1px solid rgba(95,111,82,.25); color: var(--olive); }
        .flash-error   { background: rgba(180,60,60,.08); border: 1px solid rgba(180,60,60,.2); color: #a03030; }

        /* Stats grid */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .sc {
            background: var(--white); border-radius: var(--radius);
            padding: 1.1rem 1.25rem;
            box-shadow: var(--shadow);
            display: flex; align-items: center; gap: .85rem;
            border-top: 3px solid transparent;
            transition: transform .2s;
        }
        .sc:hover { transform: translateY(-2px); }
        .sc.c1 { border-top-color: var(--camel); }
        .sc.c2 { border-top-color: var(--olive); }
        .sc.c3 { border-top-color: var(--laurel); }
        .sc.c4 { border-top-color: #e07a5f; }
        .sc-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .ic1 { background: rgba(185,148,112,.12); }
        .ic2 { background: rgba(95,111,82,.12); }
        .ic3 { background: rgba(169,179,136,.15); }
        .ic4 { background: rgba(224,122,95,.1); }
        .sc-label { font-size: .68rem; color: var(--camel); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
        .sc-val { font-family: var(--font-d); font-size: 1.65rem; color: var(--charcoal); line-height: 1; margin-top: .15rem; }

        /* Panel */
        .panel { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 1.5rem; }
        .panel-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(56,44,35,.06);
        }
        .panel-title { font-family: var(--font-d); font-size: 1rem; color: var(--charcoal); }
        .panel-link { font-size: .76rem; color: var(--olive); text-decoration: none; font-weight: 600; }
        .panel-link:hover { text-decoration: underline; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: rgba(56,44,35,.025); }
        th { text-align: left; padding: .6rem 1.25rem; font-size: .67rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: rgba(56,44,35,.38); white-space: nowrap; }
        td { padding: .8rem 1.25rem; font-size: .82rem; color: var(--charcoal); border-top: 1px solid rgba(56,44,35,.05); vertical-align: middle; }
        tr:hover td { background: rgba(56,44,35,.012); }

        /* Badge */
        .badge { display: inline-block; padding: .18rem .6rem; border-radius: 999px; font-size: .67rem; font-weight: 700; }
        .b-pending  { background: rgba(185,148,112,.12); color: #7a4e20; }
        .b-approved { background: rgba(95,111,82,.12);   color: var(--olive); }
        .b-rejected { background: rgba(180,60,60,.08);   color: #a03030; }
        .b-user     { background: rgba(169,179,136,.15); color: #3a5c2a; }

        /* Action buttons */
        .btn-sm {
            padding: .28rem .7rem; border-radius: 6px;
            font-family: var(--font-b); font-size: .73rem; font-weight: 600;
            cursor: pointer; transition: all .15s; border: 1.5px solid transparent;
            text-decoration: none; display: inline-block;
        }
        .btn-approve { background: var(--olive); color: white; border-color: var(--olive); }
        .btn-approve:hover { background: var(--charcoal); border-color: var(--charcoal); }
        .btn-view { background: transparent; color: var(--olive); border-color: rgba(95,111,82,.35); }
        .btn-view:hover { background: rgba(95,111,82,.07); }

        /* Empty */
        .empty { text-align: center; padding: 3rem 1rem; color: var(--camel); }
        .empty .ei { font-size: 2rem; margin-bottom: .6rem; opacity: .4; }
        .empty p { font-size: .82rem; }

        /* Two col */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

        @media (max-width: 900px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .two-col { grid-template-columns: 1fr; }
            .sidenav { display: none; }
        }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<header>
    <div class="h-brand">🌿 EcoEats</div>
    <div class="h-sep"></div>
    <div class="h-label">Admin Panel</div>
    <div class="h-right">
        <span class="h-user">Halo, <strong>{{ auth()->user()->name }}</strong></span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="btn-out">Keluar</button>
        </form>
    </div>
</header>

<div class="layout">

    {{-- SIDENAV --}}
    <aside class="sidenav">
        <div class="sn-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="sn-item active">📊 Dashboard</a>
        <div class="sn-label">Manajemen</div>
        <a href="{{ route('admin.merchants.index') }}" class="sn-item">🏪 Verifikasi Merchant</a>
        <a href="{{ route('admin.users.index') }}" class="sn-item">👥 Pengguna</a>
        <a href="{{ route('admin.food-listings.index') }}" class="sn-item">🍱 Food Listing</a>
        <a href="{{ route('admin.orders.index') }}" class="sn-item">📋 Semua Pesanan</a>
        <a href="{{ route('admin.withdrawals.index') }}" class="sn-item">💰 Penarikan Dana</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
        <a href="{{ route('admin.map') }}" class="sn-item">🗺 Peta Merchant</a>
    </aside>

    {{-- CONTENT --}}
    <main class="content">

        {{-- Flash --}}
        @if(session('success'))
            <div class="flash flash-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">⚠️ {{ session('error') }}</div>
        @endif

        <div class="pg-head">
            <h1>Dashboard Admin</h1>
            <div class="pg-crumb">EcoEats › Admin › Dashboard</div>
        </div>

        {{-- Stats --}}
        <div class="stats">
            <div class="sc c1">
                <div class="sc-icon ic1">⏳</div>
                <div>
                    <div class="sc-label">Pending Verifikasi</div>
                    <div class="sc-val">{{ $stats['pending_verifications'] }}</div>
                </div>
            </div>
            <div class="sc c2">
                <div class="sc-icon ic2">✅</div>
                <div>
                    <div class="sc-label">Merchant Aktif</div>
                    <div class="sc-val">{{ $stats['active_merchants'] }}</div>
                </div>
            </div>
            <div class="sc c3">
                <div class="sc-icon ic3">👥</div>
                <div>
                    <div class="sc-label">Total User</div>
                    <div class="sc-val">{{ $stats['total_users'] }}</div>
                </div>
            </div>
            <div class="sc c4">
                <div class="sc-icon ic4">🍱</div>
                <div>
                    <div class="sc-label">Listing Aktif</div>
                    <div class="sc-val">{{ $stats['active_listings'] }}</div>
                </div>
            </div>
        </div>

        {{-- Merchant pending --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-title">Pengajuan Verifikasi Terbaru</div>
                <a href="{{ route('admin.merchants.index', ['status' => 'pending']) }}" class="panel-link">
                    Lihat semua →
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Usaha</th>
                        <th>Pemilik</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingMerchants as $m)
                    <tr>
                        <td><strong>{{ $m->business_name }}</strong></td>
                        <td>{{ $m->user->name }}</td>
                        <td>{{ $m->created_at->format('d M Y') }}</td>
                        <td><span class="badge b-pending">Pending</span></td>
                        <td>
                            <a href="{{ route('admin.merchants.show', $m) }}" class="btn-sm btn-view">Tinjau</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty">
                                <div class="ei">📄</div>
                                <p>Tidak ada pengajuan pending.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Two-col: pengguna terbaru + ringkasan listing --}}
        <div class="two-col">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">Pengguna Terbaru</div>
                    <a href="{{ route('admin.users.index') }}" class="panel-link">Lihat semua →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td style="color:var(--camel)">{{ $u->email }}</td>
                            <td>{{ $u->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty">
                                    <div class="ei">👥</div>
                                    <p>Belum ada pengguna.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">Aksi Cepat</div>
                </div>
                <div style="padding: 1.25rem; display:flex; flex-direction:column; gap:.75rem;">
                    <a href="{{ route('admin.merchants.index', ['status'=>'pending']) }}"
                       style="display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; border-radius:10px; background:rgba(185,148,112,.08); border:1.5px solid rgba(185,148,112,.2); text-decoration:none; color:var(--charcoal); font-size:.83rem; font-weight:600; transition:background .2s;"
                       onmouseover="this.style.background='rgba(185,148,112,.15)'" onmouseout="this.style.background='rgba(185,148,112,.08)'">
                        <span style="font-size:1.2rem">⏳</span>
                        <div>
                            <div>Review Merchant Pending</div>
                            <div style="font-size:.72rem; color:var(--camel); font-weight:400">{{ $stats['pending_verifications'] }} pengajuan menunggu</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.food-listings.index') }}"
                       style="display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; border-radius:10px; background:rgba(95,111,82,.07); border:1.5px solid rgba(95,111,82,.15); text-decoration:none; color:var(--charcoal); font-size:.83rem; font-weight:600; transition:background .2s;"
                       onmouseover="this.style.background='rgba(95,111,82,.12)'" onmouseout="this.style.background='rgba(95,111,82,.07)'">
                        <span style="font-size:1.2rem">🍱</span>
                        <div>
                            <div>Kelola Food Listing</div>
                            <div style="font-size:.72rem; color:var(--camel); font-weight:400">{{ $stats['active_listings'] }} listing aktif saat ini</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       style="display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; border-radius:10px; background:rgba(169,179,136,.1); border:1.5px solid rgba(169,179,136,.2); text-decoration:none; color:var(--charcoal); font-size:.83rem; font-weight:600; transition:background .2s;"
                       onmouseover="this.style.background='rgba(169,179,136,.18)'" onmouseout="this.style.background='rgba(169,179,136,.1)'">
                        <span style="font-size:1.2rem">👥</span>
                        <div>
                            <div>Manajemen Pengguna</div>
                            <div style="font-size:.72rem; color:var(--camel); font-weight:400">{{ $stats['total_users'] }} user terdaftar</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>