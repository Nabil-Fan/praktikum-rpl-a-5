{{--
    resources/views/admin/merchant-verification/index.blade.php
    Data dari: App\Http\Controllers\Admin\MerchantVerificationController@index
    Variabel: $merchants (Paginator), $status (string), $counts (array)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Merchant — EcoEats Admin</title>
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

        /* Filter tabs */
        .filter-tabs { display:flex; gap:.4rem; margin-bottom:1.5rem; flex-wrap:wrap; }
        .ftab {
            padding:.35rem .9rem; border-radius:999px; font-size:.78rem; font-weight:600;
            border:1.5px solid rgba(56,44,35,.12); color:rgba(56,44,35,.5);
            text-decoration:none; transition:all .15s; display:inline-flex; align-items:center; gap:.4rem;
        }
        .ftab:hover { border-color:var(--laurel); color:var(--olive); }
        .ftab.active { background:var(--olive); color:white; border-color:var(--olive); }
        .ftab-count { background:rgba(255,255,255,.25); border-radius:999px; padding:.05rem .45rem; font-size:.68rem; }
        .ftab.active .ftab-count { background:rgba(255,255,255,.25); }
        .ftab:not(.active) .ftab-count { background:rgba(56,44,35,.08); color:var(--charcoal); }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        thead tr { background:rgba(56,44,35,.025); }
        th { text-align:left; padding:.6rem 1.25rem; font-size:.67rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:rgba(56,44,35,.38); white-space:nowrap; }
        td { padding:.8rem 1.25rem; font-size:.82rem; color:var(--charcoal); border-top:1px solid rgba(56,44,35,.05); vertical-align:middle; }
        tr:hover td { background:rgba(56,44,35,.012); }

        .badge { display:inline-block; padding:.18rem .6rem; border-radius:999px; font-size:.67rem; font-weight:700; }
        .b-pending  { background:rgba(185,148,112,.12); color:#7a4e20; }
        .b-approved { background:rgba(95,111,82,.12); color:var(--olive); }
        .b-rejected { background:rgba(180,60,60,.08); color:#a03030; }

        .btn-sm { padding:.28rem .7rem; border-radius:6px; font-family:var(--font-b); font-size:.73rem; font-weight:600; cursor:pointer; transition:all .15s; border:1.5px solid transparent; text-decoration:none; display:inline-block; }
        .btn-view { background:transparent; color:var(--olive); border-color:rgba(95,111,82,.35); }
        .btn-view:hover { background:rgba(95,111,82,.07); }

        /* Biz info cell */
        .biz-name { font-weight:600; }
        .biz-addr { font-size:.74rem; color:var(--camel); margin-top:.15rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px; }

        /* Doc indicator */
        .doc-pill { display:inline-flex; align-items:center; gap:.25rem; font-size:.7rem; padding:.15rem .5rem; border-radius:6px; font-weight:600; }
        .doc-ok  { background:rgba(95,111,82,.1); color:var(--olive); }
        .doc-no  { background:rgba(56,44,35,.06); color:rgba(56,44,35,.4); }

        .empty { text-align:center; padding:3rem; color:var(--camel); }
        .empty .ei { font-size:2rem; margin-bottom:.6rem; opacity:.4; }
        .empty p { font-size:.82rem; }

        /* Pagination */
        .pager { display:flex; justify-content:flex-end; padding:1rem 1.25rem; border-top:1px solid rgba(56,44,35,.06); gap:.4rem; }
        .pager a, .pager span { padding:.3rem .65rem; border-radius:6px; font-size:.78rem; font-weight:600; text-decoration:none; border:1.5px solid rgba(56,44,35,.1); color:var(--olive); transition:all .15s; }
        .pager a:hover { background:rgba(95,111,82,.07); }
        .pager span.current { background:var(--olive); color:white; border-color:var(--olive); }
        .pager span.disabled { color:rgba(56,44,35,.25); cursor:default; }
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
        <a href="{{ route('admin.merchants.index') }}" class="sn-item active">🏪 Verifikasi Merchant</a>
        <a href="{{ route('admin.users.index') }}" class="sn-item">👥 Pengguna</a>
        <a href="{{ route('admin.food-listings.index') }}" class="sn-item">🍱 Food Listing</a>
        <a href="{{ route('admin.orders.index') }}" class="sn-item">📋 Semua Pesanan</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
    </aside>

    <main class="content">
        @if(session('success'))
            <div class="flash flash-success">✅ {{ session('success') }}</div>
        @endif

        <div class="pg-head">
            <h1>Verifikasi Merchant</h1>
            <div class="pg-crumb">EcoEats › Admin › Verifikasi Merchant</div>
        </div>

        <div class="filter-tabs">
            @foreach([
                'all'      => ['label' => 'Semua',    'count' => $counts['all']],
                'pending'  => ['label' => 'Pending',  'count' => $counts['pending']],
                'approved' => ['label' => 'Disetujui','count' => $counts['approved']],
                'rejected' => ['label' => 'Ditolak',  'count' => $counts['rejected']],
            ] as $key => $item)
            <a href="{{ route('admin.merchants.index', ['status' => $key]) }}"
               class="ftab {{ $status === $key ? 'active' : '' }}">
                {{ $item['label'] }}
                <span class="ftab-count">{{ $item['count'] }}</span>
            </a>
            @endforeach
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Usaha</th>
                        <th>Pemilik</th>
                        <th>Dokumen</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($merchants as $m)
                    <tr>
                        <td style="color:var(--camel); font-size:.75rem;">{{ $m->id }}</td>
                        <td>
                            <div class="biz-name">{{ $m->business_name }}</div>
                            <div class="biz-addr">{{ $m->business_address }}</div>
                        </td>
                        <td>
                            <div>{{ $m->user->name }}</div>
                            <div style="font-size:.74rem; color:var(--camel)">{{ $m->user->email }}</div>
                        </td>
                        <td>
                            <span class="doc-pill {{ $m->business_license_url ? 'doc-ok' : 'doc-no' }}">
                                {{ $m->business_license_url ? '✓' : '✗' }} SIUP
                            </span>
                            <span class="doc-pill {{ $m->halal_cert_url ? 'doc-ok' : 'doc-no' }}" style="margin-left:.25rem">
                                {{ $m->halal_cert_url ? '✓' : '✗' }} Halal
                            </span>
                        </td>
                        <td>{{ $m->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge b-{{ $m->verification_status }}">
                                {{ match($m->verification_status) {
                                    'pending'  => 'Pending',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                } }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.merchants.show', $m) }}" class="btn-sm btn-view">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <div class="ei">📄</div>
                                <p>Tidak ada pengajuan merchant dengan status ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($merchants->hasPages())
            <div class="pager">
                {{ $merchants->links() }}
            </div>
            @endif
        </div>
    </main>
</div>
</body>
</html>