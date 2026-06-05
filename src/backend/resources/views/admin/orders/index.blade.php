{{-- resources/views/admin/orders/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Pesanan — EcoEats Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:    #5F6F52; --laurel:  #A9B388;
            --cornsilk: #FEFAE0; --camel:   #B99470;
            --charcoal: #382C23; --bg:      #edeae3;
            --font-d: 'Fraunces', serif; --font-b: 'Plus Jakarta Sans', sans-serif;
        }
        body { font-family: var(--font-b); background: var(--bg); color: var(--charcoal); min-height: 100vh; }

        /* TOPBAR */
        


        header { background:var(--charcoal); height:58px; display:flex; align-items:center; padding:0 1.75rem; gap:1.25rem; position:sticky; top:0; z-index:100; }
        .h-brand { font-family:var(--font-d); font-size:1.15rem; color:var(--cornsilk); }
        .h-sep { width:1px; height:18px; background:rgba(254,250,224,.12); }
        .h-label { font-size:.68rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--camel); }
        .h-right { margin-left:auto; display:flex; align-items:center; gap:1rem; }
        .h-user { font-size:.8rem; color:rgba(254,250,224,.65); }
        .h-user strong { color:var(--cornsilk); }
        .btn-out { padding:.32rem .8rem; background:transparent; border:1.5px solid rgba(254,250,224,.18); color:rgba(254,250,224,.55); border-radius:999px; font-family:var(--font-b); font-size:.73rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-out:hover { border-color:rgba(254,250,224,.45); color:var(--cornsilk); }

        /* LAYOUT */
        .layout { display: flex; min-height: calc(100vh - 56px); }

        /* SIDENAV */
        .sidenav { width: 210px; background: white; padding: 1.25rem 0; border-right: 1px solid rgba(56,44,35,.07); position: sticky; top: 56px; height: calc(100vh - 56px); overflow-y: auto; flex-shrink: 0; }
        .sn-label { font-size: .6rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: rgba(56,44,35,.28); padding: .6rem 1.25rem .2rem; }
        .sn-item { display: flex; align-items: center; gap: .6rem; padding: .5rem 1.25rem; font-size: .82rem; color: rgba(56,44,35,.5); text-decoration: none; border-left: 2px solid transparent; transition: all .15s; }
        .sn-item:hover { color: var(--charcoal); background: rgba(56,44,35,.03); }
        .sn-item.active { color: var(--charcoal); border-left-color: var(--olive); background: rgba(95,111,82,.06); font-weight: 600; }

        /* CONTENT */
        .content { flex: 1; padding: 1.75rem 2rem 3rem; overflow-x: auto; min-width: 0; }

        .pg-head { margin-bottom: 1.5rem; }
        .pg-title { font-family: var(--font-d); font-size: 1.45rem; }
        .breadcrumb { font-size: .72rem; color: var(--camel); margin-top: .2rem; }

        /* Flash */
        .flash { padding: .7rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .82rem; font-weight: 500; }
        .flash-success { background: rgba(95,111,82,.1); color: var(--olive); border: 1px solid rgba(95,111,82,.2); }
        .flash-error   { background: rgba(180,60,60,.07); color: #a03030; border: 1px solid rgba(180,60,60,.15); }

        /* Summary strip */
        .summary-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .sum-card { background: white; border-radius: 12px; padding: 1rem 1.2rem; box-shadow: 0 1px 3px rgba(56,44,35,.07); display: flex; align-items: center; gap: .8rem; }
        .sum-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .si-camel  { background: rgba(185,148,112,.12); }
        .si-olive  { background: rgba(95,111,82,.12); }
        .si-laurel { background: rgba(169,179,136,.15); }
        .si-red    { background: rgba(180,60,60,.07); }
        .sum-label { font-size: .67rem; color: var(--camel); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
        .sum-value { font-family: var(--font-d); font-size: 1.5rem; color: var(--charcoal); line-height: 1.1; }

        /* Filter bar */
        .filter-bar { display: flex; align-items: center; gap: .75rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
        .search-input {
            flex: 1; min-width: 200px; max-width: 320px;
            padding: .5rem .9rem; border: 1.5px solid rgba(56,44,35,.12);
            border-radius: 9px; font-family: var(--font-b); font-size: .82rem;
            color: var(--charcoal); background: white; outline: none;
            transition: border-color .2s;
        }
        .search-input:focus { border-color: var(--laurel); }
        .tab-bar { display: flex; gap: .3rem; flex-wrap: wrap; }
        .tab-link { display: inline-flex; align-items: center; gap: .35rem; padding: .32rem .8rem; border-radius: 999px; font-size: .73rem; font-weight: 600; text-decoration: none; border: 1.5px solid rgba(56,44,35,.1); color: rgba(56,44,35,.5); background: white; transition: all .15s; }
        .tab-link:hover { border-color: var(--laurel); color: var(--olive); }
        .tab-link.active { background: var(--charcoal); color: var(--cornsilk); border-color: var(--charcoal); }
        .tab-count { font-size: .62rem; }
        .tab-link:not(.active) .tab-count { color: rgba(56,44,35,.4); }

        /* Panel */
        .panel { background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(56,44,35,.07); overflow: hidden; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: rgba(56,44,35,.025); }
        th { text-align: left; padding: .6rem 1.1rem; font-size: .67rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: rgba(56,44,35,.38); white-space: nowrap; }
        td { padding: .75rem 1.1rem; font-size: .81rem; border-top: 1px solid rgba(56,44,35,.045); vertical-align: middle; }
        tr:hover td { background: rgba(56,44,35,.012); }

        /* Badge */
        .badge { display: inline-block; padding: .2rem .6rem; border-radius: 999px; font-size: .67rem; font-weight: 700; white-space: nowrap; }
        .b-pending   { background: rgba(185,148,112,.12); color: #8a5c2a; }
        .b-confirmed { background: rgba(95,111,82,.12);   color: var(--olive); }
        .b-ready     { background: rgba(169,179,136,.2);  color: #3d5c2a; }
        .b-completed { background: rgba(95,111,82,.08);   color: var(--olive); }
        .b-rejected  { background: rgba(180,60,60,.08);   color: #a03030; }
        .b-expired   { background: rgba(56,44,35,.07);    color: rgba(56,44,35,.4); }

        /* Pay badge */
        .pay-method { font-size: .72rem; color: var(--camel); text-transform: uppercase; font-weight: 600; }

        /* Btn */
        .btn-sm { padding: .25rem .7rem; border-radius: 7px; font-family: var(--font-b); font-size: .72rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; transition: background .15s; }
        .btn-view { background: rgba(56,44,35,.07); color: var(--charcoal); }
        .btn-view:hover { background: rgba(56,44,35,.13); }

        /* Total */
        .amount { font-family: var(--font-d); font-size: .95rem; color: var(--olive); }

        /* Empty */
        .empty-td { text-align: center; padding: 3rem; color: var(--camel); font-size: .82rem; }
        .empty-icon { font-size: 1.75rem; opacity: .4; margin-bottom: .5rem; }

        /* Pagination */
        .pagination { display: flex; gap: .3rem; justify-content: center; margin-top: 1.25rem; }
        .pagination a, .pagination span { padding: .32rem .7rem; border-radius: 8px; font-size: .76rem; font-weight: 600; text-decoration: none; border: 1.5px solid rgba(56,44,35,.1); color: rgba(56,44,35,.5); background: white; }
        .pagination .active-page { background: var(--charcoal); color: white; border-color: var(--charcoal); }

        @media (max-width: 900px) { .summary-row { grid-template-columns: 1fr 1fr; } }
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
            @csrf
            <button type="submit" class="btn-out">Keluar</button>
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
        <a href="{{ route('admin.orders.index') }}" class="sn-item active">📋 Semua Pesanan</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
    </aside>

    <main class="content">

        <div class="pg-head">
            <div class="pg-title">Monitoring Pesanan</div>
            <div class="breadcrumb">EcoEats › Admin › Semua Pesanan</div>
        </div>

        @if(session('success'))
            <div class="flash flash-success">✓ {{ session('success') }}</div>
        @endif

        {{-- SUMMARY STRIP --}}
        <div class="summary-row">
            <div class="sum-card">
                <div class="sum-icon si-camel">⏳</div>
                <div>
                    <div class="sum-label">Menunggu</div>
                    <div class="sum-value">{{ $counts['pending'] }}</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon si-olive">✅</div>
                <div>
                    <div class="sum-label">Selesai</div>
                    <div class="sum-value">{{ $counts['completed'] }}</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon si-laurel">📋</div>
                <div>
                    <div class="sum-label">Total Pesanan</div>
                    <div class="sum-value">{{ $counts['all'] }}</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon si-red">✕</div>
                <div>
                    <div class="sum-label">Ditolak</div>
                    <div class="sum-value">{{ $counts['rejected'] }}</div>
                </div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('admin.orders.index') }}" style="display:contents">
                <input class="search-input" type="text" name="search"
                       value="{{ $search }}"
                       placeholder="Cari kode, user, atau merchant…">
                <input type="hidden" name="status" value="{{ $status }}">
                <button type="submit" style="padding:.5rem .9rem; background:var(--olive); color:white; border:none; border-radius:9px; font-family:var(--font-b); font-size:.8rem; font-weight:600; cursor:pointer;">
                    Cari
                </button>
            </form>

            <div class="tab-bar">
                @php
                    $tabs = [
                        'all'       => 'Semua',
                        'pending'   => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'ready'     => 'Siap Diambil',
                        'completed' => 'Selesai',
                        'rejected'  => 'Ditolak',
                        'expired'   => 'Kedaluwarsa',
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <a href="{{ route('admin.orders.index', ['status' => $key, 'search' => $search]) }}"
                       class="tab-link {{ $status === $key ? 'active' : '' }}">
                        {{ $label }}
                        <span class="tab-count">({{ $counts[$key] }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- TABLE --}}
        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Kode Pickup</th>
                        <th>Pembeli</th>
                        <th>Merchant</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <span style="font-family:var(--font-d); letter-spacing:.06em;">
                                {{ $order->pickup_code }}
                            </span>
                        </td>
                        <td>{{ $order->user?->name ?? '—' }}</td>
                        <td style="color:var(--camel);">{{ $order->merchant?->business_name ?? '—' }}</td>
                        <td style="color:var(--camel);">{{ $order->items->count() }} item</td>
                        <td><span class="amount">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span></td>
                        <td><span class="pay-method">{{ $order->payment_method }}</span></td>
                        <td>
                            @php
                                $bc = match($order->status) {
                                    'pending'   => 'b-pending',
                                    'confirmed' => 'b-confirmed',
                                    'ready'     => 'b-ready',
                                    'completed' => 'b-completed',
                                    'rejected'  => 'b-rejected',
                                    default     => 'b-expired',
                                };
                            @endphp
                            <span class="badge {{ $bc }}">{{ $order->statusLabel() }}</span>
                        </td>
                        <td style="color:var(--camel); font-size:.75rem; white-space:nowrap;">
                            {{ $order->ordered_at->format('d M, H:i') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-sm btn-view">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-td">
                                <div class="empty-icon">📋</div>
                                Belum ada pesanan{{ $status !== 'all' ? ' dengan status ini' : '' }}{{ $search ? ' yang cocok dengan pencarian' : '' }}.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($orders->hasPages())
            <div class="pagination">
                @if($orders->onFirstPage())
                    <span>‹</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}">‹</a>
                @endif
                @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if($page == $orders->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}">›</a>
                @else
                    <span>›</span>
                @endif
            </div>
        @endif

    </main>
</div>
</body>
</html>