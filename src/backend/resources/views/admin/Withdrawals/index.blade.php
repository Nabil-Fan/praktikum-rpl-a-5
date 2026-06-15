{{--
    resources/views/admin/withdrawals/index.blade.php
    Data dari: Admin\WithdrawalController@index
    Variabel: $withdrawals (Paginator), $status, $search, $counts (array)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penarikan Dana — EcoEats Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#eee9df; --white:#fff;
            --font-d:'Fraunces',serif; --font-b:'Plus Jakarta Sans',sans-serif;
            --shadow:0 1px 3px rgba(56,44,35,.06),0 4px 16px rgba(56,44,35,.07);
        }
        body { font-family:var(--font-b); background:var(--bg); color:var(--charcoal); min-height:100vh; }

        header { background:var(--charcoal); height:58px; display:flex; align-items:center; padding:0 1.75rem; gap:1.25rem; position:sticky; top:0; z-index:100; }
        .h-brand { font-family:var(--font-d); font-size:1.15rem; color:var(--cornsilk); }
        .h-sep { width:1px; height:18px; background:rgba(254,250,224,.12); }
        .h-label { font-size:.68rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--camel); }
        .h-right { margin-left:auto; display:flex; align-items:center; gap:1rem; }
        .h-user { font-size:.8rem; color:rgba(254,250,224,.65); }
        .h-user strong { color:var(--cornsilk); }
        .btn-out { padding:.32rem .8rem; background:transparent; border:1.5px solid rgba(254,250,224,.18); color:rgba(254,250,224,.55); border-radius:999px; font-family:var(--font-b); font-size:.73rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-out:hover { border-color:rgba(254,250,224,.45); color:var(--cornsilk); }

        .layout { display:flex; min-height:calc(100vh - 58px); }
        .sidenav { width:210px; background:var(--white); border-right:1px solid rgba(56,44,35,.07); padding:1.25rem 0; position:sticky; top:58px; height:calc(100vh - 58px); overflow-y:auto; flex-shrink:0; }
        .sn-label { font-size:.6rem; font-weight:700; letter-spacing:.13em; text-transform:uppercase; color:rgba(56,44,35,.28); padding:.65rem 1.25rem .2rem; }
        .sn-item { display:flex; align-items:center; gap:.6rem; padding:.52rem 1.25rem; font-size:.8rem; color:rgba(56,44,35,.5); text-decoration:none; border-left:2.5px solid transparent; transition:all .15s; }
        .sn-item:hover { color:var(--charcoal); background:rgba(56,44,35,.025); }
        .sn-item.active { color:var(--charcoal); border-left-color:var(--olive); background:rgba(95,111,82,.06); font-weight:600; }

        .content { flex:1; padding:2rem 2.25rem 4rem; }
        .pg-head { margin-bottom:1.5rem; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        .flash { padding:.75rem 1rem; border-radius:10px; font-size:.83rem; margin-bottom:1.25rem; }
        .flash-success { background:rgba(95,111,82,.1); border:1px solid rgba(95,111,82,.25); color:var(--olive); }
        .flash-error   { background:rgba(180,60,60,.08); border:1px solid rgba(180,60,60,.2); color:#a03030; }

        /* Summary strip */
        .summary { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.75rem; }
        .sc { background:var(--white); border-radius:12px; padding:1rem 1.25rem; box-shadow:var(--shadow); display:flex; align-items:center; gap:.75rem; border-top:3px solid transparent; }
        .sc.c1 { border-top-color:var(--camel); }
        .sc.c2 { border-top-color:var(--olive); }
        .sc.c3 { border-top-color:var(--laurel); }
        .sc.c4 { border-top-color:#e07a5f; }
        .sc-icon { width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
        .si-1 { background:rgba(185,148,112,.12); }
        .si-2 { background:rgba(95,111,82,.12); }
        .si-3 { background:rgba(169,179,136,.15); }
        .si-4 { background:rgba(180,60,60,.08); }
        .sc-label { font-size:.67rem; color:var(--camel); font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
        .sc-val { font-family:var(--font-d); font-size:1.5rem; color:var(--charcoal); line-height:1.1; }

        /* Toolbar */
        .toolbar { display:flex; gap:.75rem; margin-bottom:1.25rem; flex-wrap:wrap; align-items:center; }
        .search-wrap { display:flex; flex:1; min-width:220px; }
        .search-input { flex:1; padding:.55rem .9rem; font-family:var(--font-b); font-size:.83rem; color:var(--charcoal); background:var(--white); border:1.5px solid rgba(56,44,35,.12); border-radius:9px 0 0 9px; outline:none; transition:border-color .2s; }
        .search-input:focus { border-color:var(--laurel); }
        .search-btn { padding:.55rem .9rem; background:var(--olive); color:white; border:none; border-radius:0 9px 9px 0; font-family:var(--font-b); font-size:.83rem; font-weight:600; cursor:pointer; transition:background .2s; }
        .search-btn:hover { background:var(--charcoal); }
        .filter-tabs { display:flex; gap:.4rem; flex-wrap:wrap; }
        .ftab { padding:.32rem .8rem; border-radius:999px; font-size:.75rem; font-weight:600; border:1.5px solid rgba(56,44,35,.12); color:rgba(56,44,35,.5); text-decoration:none; transition:all .15s; display:inline-flex; align-items:center; gap:.35rem; }
        .ftab:hover { border-color:var(--laurel); color:var(--olive); }
        .ftab.active { background:var(--olive); color:white; border-color:var(--olive); }
        .ftab-count { font-size:.67rem; padding:.05rem .4rem; border-radius:999px; }
        .ftab.active .ftab-count { background:rgba(255,255,255,.25); }
        .ftab:not(.active) .ftab-count { background:rgba(56,44,35,.08); color:var(--charcoal); }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        thead tr { background:rgba(56,44,35,.025); }
        th { text-align:left; padding:.6rem 1.25rem; font-size:.67rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:rgba(56,44,35,.38); white-space:nowrap; }
        td { padding:.8rem 1.25rem; font-size:.82rem; color:var(--charcoal); border-top:1px solid rgba(56,44,35,.05); vertical-align:middle; }
        tr:hover td { background:rgba(56,44,35,.012); }

        .badge { display:inline-block; padding:.18rem .6rem; border-radius:999px; font-size:.67rem; font-weight:700; }
        .b-pending    { background:rgba(185,148,112,.12); color:#7a4e20; }
        .b-processing { background:rgba(95,111,82,.12);   color:var(--olive); }
        .b-completed  { background:rgba(95,111,82,.08);   color:var(--olive); }
        .b-rejected   { background:rgba(180,60,60,.08);   color:#a03030; }

        .amount-val { font-family:var(--font-d); color:var(--olive); }
        .btn-sm { padding:.27rem .7rem; border-radius:6px; font-family:var(--font-b); font-size:.73rem; font-weight:600; cursor:pointer; border:1.5px solid transparent; text-decoration:none; display:inline-block; transition:all .15s; }
        .btn-view { background:transparent; color:var(--olive); border-color:rgba(95,111,82,.3); }
        .btn-view:hover { background:rgba(95,111,82,.07); }

        .empty { text-align:center; padding:3rem; color:var(--camel); }
        .empty .ei { font-size:2rem; margin-bottom:.6rem; opacity:.4; }
        .empty p { font-size:.82rem; }

        .pager-row { display:flex; justify-content:flex-end; padding:1rem 1.25rem; border-top:1px solid rgba(56,44,35,.06); }

        @media(max-width:900px) { .summary { grid-template-columns:1fr 1fr; } .sidenav { display:none; } }
    </style>
</head>
<body>

<header>
    <div class="h-brand">🌿 EcoEats</div>
    <div class="h-sep"></div>
    <div class="h-label">Admin Panel</div>
    <div class="h-right">
        <span class="h-user">Halo, <strong>{{ auth()->user()->name }}</strong></span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf <button type="submit" class="btn-out">Keluar</button>
        </form>
    </div>
</header>

<div class="layout">
    <aside class="sidenav">
        <div class="sn-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="sn-item">📊 Dashboard</a>
        <div class="sn-label">Manajemen</div>
        <a href="{{ route('admin.merchants.index') }}" class="sn-item">🏪 Verifikasi Merchant</a>
        <a href="{{ route('admin.users.index') }}" class="sn-item">👥 Pengguna</a>
        <a href="{{ route('admin.food-listings.index') }}" class="sn-item">🍱 Food Listing</a>
        <a href="{{ route('admin.orders.index') }}" class="sn-item">📋 Semua Pesanan</a>
        <a href="{{ route('admin.withdrawals.index') }}" class="sn-item active">💰 Penarikan Dana</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
        <a href="{{ route('admin.map') }}" class="sn-item">🗺 Peta Merchant</a>
    </aside>

    <main class="content">
        @if(session('success'))<div class="flash flash-success">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="flash flash-error">⚠️ {{ session('error') }}</div>@endif

        <div class="pg-head">
            <h1>Penarikan Dana</h1>
            <div class="pg-crumb">EcoEats › Admin › Penarikan Dana</div>
        </div>

        <div class="summary">
            <div class="sc c1">
                <div class="sc-icon si-1">⏳</div>
                <div>
                    <div class="sc-label">Menunggu</div>
                    <div class="sc-val">{{ $counts['pending'] }}</div>
                </div>
            </div>
            <div class="sc c2">
                <div class="sc-icon si-2">🔄</div>
                <div>
                    <div class="sc-label">Diproses</div>
                    <div class="sc-val">{{ $counts['processing'] }}</div>
                </div>
            </div>
            <div class="sc c3">
                <div class="sc-icon si-3">✅</div>
                <div>
                    <div class="sc-label">Selesai</div>
                    <div class="sc-val">{{ $counts['completed'] }}</div>
                </div>
            </div>
            <div class="sc c4">
                <div class="sc-icon si-4">✕</div>
                <div>
                    <div class="sc-label">Ditolak</div>
                    <div class="sc-val">{{ $counts['rejected'] }}</div>
                </div>
            </div>
        </div>

        <div class="toolbar">
            <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="search-wrap">
                <input type="hidden" name="status" value="{{ $status }}">
                <input class="search-input" type="text" name="search"
                       value="{{ $search }}" placeholder="Cari nama merchant…">
                <button type="submit" class="search-btn">Cari</button>
            </form>
            <div class="filter-tabs">
                @foreach(['all'=>'Semua','pending'=>'Menunggu','processing'=>'Diproses','completed'=>'Selesai','rejected'=>'Ditolak'] as $key => $label)
                <a href="{{ route('admin.withdrawals.index', ['status'=>$key,'search'=>$search]) }}"
                   class="ftab {{ $status===$key ? 'active' : '' }}">
                    {{ $label }}
                    <span class="ftab-count">{{ $counts[$key] ?? $counts['all'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Merchant</th>
                        <th>Nominal</th>
                        <th>Bank</th>
                        <th>Tanggal Ajuan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                    <tr>
                        <td style="color:var(--camel);font-size:.75rem">{{ $w->id }}</td>
                        <td>
                            <div style="font-weight:600">{{ $w->merchant->business_name ?? '—' }}</div>
                            <div style="font-size:.73rem;color:var(--camel)">{{ $w->merchant->user->name ?? '' }}</div>
                        </td>
                        <td><span class="amount-val">Rp{{ number_format($w->amount, 0, ',', '.') }}</span></td>
                        <td>
                            <div style="font-weight:600">{{ $w->bank_name }}</div>
                            <div style="font-size:.73rem;color:var(--camel)">{{ $w->bank_account_number }}</div>
                        </td>
                        <td style="font-size:.75rem;color:var(--camel)">{{ $w->requested_at->format('d M Y') }}</td>
                        <td><span class="badge b-{{ $w->status }}">{{ $w->statusLabel() }}</span></td>
                        <td>
                            <a href="{{ route('admin.withdrawals.show', $w) }}" class="btn-sm btn-view">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <div class="ei">💰</div>
                                <p>Tidak ada permintaan penarikan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($withdrawals->hasPages())
            <div class="pager-row">{{ $withdrawals->links() }}</div>
            @endif
        </div>
    </main>
</div>
</body>
</html>