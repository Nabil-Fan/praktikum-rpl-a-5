{{--
    resources/views/dashboard/merchant.blade.php
    Data dari: App\Http\Controllers\Merchant\DashboardController@index
    Variabel: $profile (MerchantProfile|null), $stats (array), $recentListings (Collection)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — EcoEats Merchant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --olive:    #5F6F52;
            --laurel:   #A9B388;
            --cornsilk: #FEFAE0;
            --camel:    #B99470;
            --charcoal: #382C23;
            --bg:       #f2ede4;
            --white:    #ffffff;
            --font-d:   'Fraunces', serif;
            --font-b:   'Plus Jakarta Sans', sans-serif;
            --shadow:   0 1px 3px rgba(56,44,35,.06), 0 4px 16px rgba(56,44,35,.07);
        }

        body { font-family: var(--font-b); background: var(--bg); color: var(--charcoal); display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar { width:230px; min-height:100vh; background:var(--charcoal); display:flex; flex-direction:column; position:fixed; top:0; left:0; z-index:50; }
        .sb-brand { padding:1.4rem 1.4rem 1rem; border-bottom:1px solid rgba(254,250,224,.07); }
        .sb-brand-name { font-family:var(--font-d); font-size:1.2rem; color:var(--cornsilk); }
        .sb-role { font-size:.65rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--camel); margin-top:.2rem; }
        .sb-user { padding:1rem 1.4rem; border-bottom:1px solid rgba(254,250,224,.07); }
        .sb-name { font-size:.83rem; font-weight:600; color:var(--cornsilk); }


        @php $verStatus = $profile?->verification_status ?? 'pending'; @endphp
        .sb-status {
            display: inline-flex; align-items: center; gap: .3rem;
            font-size: .67rem; margin-top: .2rem;
            color: {{ $verStatus === 'approved' ? 'var(--laurel)' : ($verStatus === 'rejected' ? '#f07070' : 'var(--camel)') }};
        }
         .sb-sub  { font-size:.71rem; color:var(--camel); margin-top:.2rem; }
        nav.sb-nav { flex:1; padding:.75rem 0; }
        .nav-label { font-size:.61rem; font-weight:700; letter-spacing:.13em; text-transform:uppercase; color:rgba(254,250,224,.27); padding:.65rem 1.4rem .2rem; }
        .nav-item { display:flex; align-items:center; gap:.65rem; padding:.55rem 1.4rem; font-size:.8rem; color:rgba(254,250,224,.55); text-decoration:none; border-left:2.5px solid transparent; transition:all .15s; }
        .nav-item:hover { color:var(--cornsilk); background:rgba(254,250,224,.04); }
        .nav-item.active { color:var(--cornsilk); border-left-color:var(--camel); background:rgba(185,148,112,.12); font-weight:600; }
        .sb-footer { padding:1rem 1.4rem; border-top:1px solid rgba(254,250,224,.07); }
        .btn-logout { width:100%; padding:.55rem; background:transparent; border:1.5px solid rgba(254,250,224,.13); color:rgba(254,250,224,.45); border-radius:8px; font-family:var(--font-b); font-size:.75rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-logout:hover { border-color:rgba(254,250,224,.4); color:var(--cornsilk); }

        /* ── MAIN ── */
        .main { margin-left: 230px; flex: 1; padding: 2rem 2.25rem 4rem; }

        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.75rem; gap: 1rem; }
        .page-title { font-family: var(--font-d); font-size: 1.55rem; color: var(--charcoal); }
        .page-sub { font-size: .82rem; color: var(--camel); margin-top: .2rem; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .6rem 1.2rem; background: var(--camel); color: white;
            border: none; border-radius: 10px;
            font-family: var(--font-b); font-weight: 700; font-size: .83rem;
            cursor: pointer; transition: background .2s; text-decoration: none;
            white-space: nowrap; flex-shrink: 0;
        }
        .btn-primary:hover { background: var(--charcoal); }
        .btn-primary.disabled { background: rgba(185,148,112,.4); cursor: not-allowed; pointer-events: none; }

        /* Flash */
        .flash { padding: .75rem 1rem; border-radius: 10px; font-size: .83rem; margin-bottom: 1.25rem; }
        .flash-success { background: rgba(95,111,82,.1); border: 1px solid rgba(95,111,82,.25); color: var(--olive); }
        .flash-error   { background: rgba(180,60,60,.08); border: 1px solid rgba(180,60,60,.2); color: #a03030; }

        /* Notice banner */
        .notice {
            display: flex; gap: .85rem; align-items: flex-start;
            border-radius: 12px; padding: 1rem 1.25rem;
            margin-bottom: 1.75rem; border: 1.5px solid;
        }
        .notice-pending  { background: rgba(185,148,112,.08); border-color: rgba(185,148,112,.3); }
        .notice-rejected { background: rgba(180,60,60,.06); border-color: rgba(180,60,60,.25); }
        .notice-icon { font-size: 1.2rem; flex-shrink: 0; }
        .notice-title { font-size: .85rem; font-weight: 700; color: var(--charcoal); margin-bottom: .2rem; }
        .notice-text  { font-size: .79rem; color: var(--camel); line-height: 1.55; }

        /* Stats */
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .sc {
            background: var(--white); border-radius: 14px;
            padding: 1.2rem 1.35rem; box-shadow: var(--shadow);
            border-top: 3px solid transparent;
            transition: transform .2s;
        }
        .sc:hover { transform: translateY(-2px); }
        .sc.c1 { border-top-color: var(--olive); }
        .sc.c2 { border-top-color: var(--camel); }
        .sc.c3 { border-top-color: var(--laurel); }
        .sc-label { font-size: .68rem; color: var(--camel); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .35rem; }
        .sc-val   { font-family: var(--font-d); font-size: 1.8rem; color: var(--charcoal); line-height: 1; }
        .sc-note  { font-size: .71rem; color: var(--laurel); margin-top: .3rem; }

        /* Panel */
        .panel { background: var(--white); border-radius: 14px; box-shadow: var(--shadow); overflow: hidden; margin-bottom: 1.5rem; }
        .panel-head { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; border-bottom: 1px solid rgba(56,44,35,.06); }
        .panel-title { font-family: var(--font-d); font-size: 1rem; color: var(--charcoal); }
        .panel-link { font-size: .75rem; color: var(--olive); text-decoration: none; font-weight: 600; }
        .panel-link:hover { text-decoration: underline; }

        /* Listing cards */
        .listing-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; padding: 1.25rem; }
        .lcard {
            border: 1.5px solid rgba(56,44,35,.08); border-radius: 12px; overflow: hidden;
            transition: box-shadow .2s, transform .2s;
        }
        .lcard:hover { box-shadow: 0 4px 16px rgba(56,44,35,.1); transform: translateY(-2px); }
        .lcard-thumb {
            height: 100px; background: linear-gradient(135deg, var(--laurel), var(--olive));
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; position: relative;
        }
        .lcard-status {
            position: absolute; top: .5rem; right: .5rem;
            font-size: .63rem; font-weight: 700;
            padding: .2rem .5rem; border-radius: 6px;
        }
        .ls-available   { background: var(--olive); color: white; }
        .ls-unavailable { background: rgba(56,44,35,.5); color: white; }
        .ls-sold_out    { background: #a03030; color: white; }
        .lcard-body { padding: .75rem; }
        .lcard-cat  { font-size: .67rem; color: var(--camel); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; margin-bottom: .25rem; }
        .lcard-name { font-size: .83rem; font-weight: 600; color: var(--charcoal); margin-bottom: .4rem; line-height: 1.3; }
        .lcard-price { display: flex; align-items: baseline; gap: .4rem; }
        .lcard-new  { font-family: var(--font-d); font-size: 1rem; color: var(--olive); }
        .lcard-old  { font-size: .72rem; color: #bbb; text-decoration: line-through; }
        .lcard-stock { font-size: .7rem; color: var(--camel); margin-top: .3rem; }

        /* Empty */
        .empty { text-align: center; padding: 3rem 1rem; color: var(--camel); }
        .empty .ei { font-size: 2rem; margin-bottom: .6rem; opacity: .45; }
        .empty p { font-size: .82rem; line-height: 1.6; }

        @media (max-width: 860px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .stats { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-brand-name">🌿 EcoEats</div>
        <div class="sb-role">Portal Merchant</div>
    </div>
    <div class="sb-user">
        <div class="sb-name">{{ auth()->user()->name }}</div>
        <div class="sb-sub">{{ $profile->business_name }}</div>
    </div>
    <nav class="sb-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('merchant.dashboard') }}" class="nav-item active">📊 Dashboard</a>
        <a href="{{ route('merchant.listings.index') }}" class="nav-item">🍱 Menu Surplus</a>
        <a href="{{ route('merchant.orders.index') }}" class="nav-item">📋 Pesanan Masuk</a>
        <div class="nav-label">Akun</div>
        <a href="{{ route('merchant.profile.edit') }}" class="nav-item">🏪 Profil Usaha</a>
        <a href="{{ route('merchant.map') }}" class="nav-item">🗺 Lokasi Usaha</a>
    </nav>
    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf <button type="submit" class="btn-logout">↩ Keluar</button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<main class="main">

    {{-- Flash --}}
    @if(session('success'))
        <div class="flash flash-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">⚠️ {{ session('error') }}</div>
    @endif

    <div class="topbar">
        <div>
            <div class="page-title">Selamat datang, {{ auth()->user()->name }} 👋</div>
            <div class="page-sub">
                {{ $profile ? $profile->business_name : 'Profil usaha belum diisi' }}
            </div>
        </div>
        <a href="{{ $verStatus === 'approved' ? route('merchant.listings.create') : '#' }}"
           class="btn-primary {{ $verStatus !== 'approved' ? 'disabled' : '' }}"
           title="{{ $verStatus !== 'approved' ? 'Akun harus diverifikasi dahulu' : '' }}">
            ＋ Tambah Menu Surplus
        </a>
    </div>

    {{-- Verification notice --}}
    @if($verStatus === 'pending')
    <div class="notice notice-pending">
        <span class="notice-icon">⏳</span>
        <div>
            <div class="notice-title">Akun sedang dalam proses verifikasi</div>
            <div class="notice-text">Dokumen usaha Anda sedang ditinjau tim EcoEats. Anda belum dapat mempublikasikan menu surplus hingga verifikasi disetujui oleh admin.</div>
        </div>
    </div>
    @elseif($verStatus === 'rejected')
    <div class="notice notice-rejected">
        <span class="notice-icon">❌</span>
        <div>
            <div class="notice-title">Verifikasi ditolak</div>
            <div class="notice-text">Pengajuan verifikasi Anda ditolak. Hubungi tim EcoEats untuk informasi lebih lanjut atau ajukan ulang dengan dokumen yang lengkap.</div>
        </div>
    </div>
    @endif

    {{-- Stats --}}
    <div class="stats">
        <div class="sc c1">
            <div class="sc-label">Menu Aktif</div>
            <div class="sc-val">{{ $stats['active_listings'] }}</div>
            <div class="sc-note">listing tersedia</div>
        </div>
        <div class="sc c2">
            <div class="sc-label">Pesanan Masuk</div>
            <div class="sc-val">{{ $stats['pending_orders'] }}</div>
            <div class="sc-note">menunggu konfirmasi</div>
        </div>
        <div class="sc c3">
            <div class="sc-label">Selesai Hari Ini</div>
            <div class="sc-val">{{ $stats['completed_today'] }}</div>
            <div class="sc-note">pickup berhasil</div>
        </div>
    </div>


    {{-- Recent listings --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Menu Surplus Terbaru</div>
            <a href="{{ route('merchant.listings.index') }}" class="panel-link">Kelola semua →</a>
        </div>

        @if($recentListings->isEmpty())
        <div class="empty">
            <div class="ei">🍱</div>
            <p>Belum ada menu surplus.<br>
            @if($verStatus === 'approved')
                Klik "Tambah Menu Surplus" untuk mulai.
            @else
                Tunggu verifikasi akun selesai terlebih dahulu.
            @endif
            </p>
        </div>
        @else
        <div class="listing-grid">
            @foreach($recentListings as $listing)
            <div class="lcard">
                <div class="lcard-thumb">
                    @if($listing->photo_url)
                        <img src="{{ asset('storage/' . $listing->photo_url) }}"
                             alt="{{ $listing->name }}"
                             style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                    @else
                        🍽
                    @endif
                    <span class="lcard-status ls-{{ $listing->status }}">
                        {{ match($listing->status) {
                            'available'   => 'Tersedia',
                            'sold_out'    => 'Habis',
                            default       => 'Nonaktif',
                        } }}
                    </span>
                </div>
                <div class="lcard-body">
                    <div class="lcard-cat">{{ $listing->category->name ?? '—' }}</div>
                    <div class="lcard-name">{{ $listing->name }}</div>
                    <div class="lcard-price">
                        <span class="lcard-new">Rp {{ number_format($listing->discount_price, 0, ',', '.') }}</span>
                        <span class="lcard-old">Rp {{ number_format($listing->original_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="lcard-stock">Stok: {{ $listing->stock_qty }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</main>
</body>
</html>