{{--
    resources/views/merchant/food-listings/index.blade.php
    Data dari: App\Http\Controllers\Merchant\FoodListingController@index
    Variabel: $listings (Paginator), $profile (MerchantProfile)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Surplus — EcoEats Merchant</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#f2ede4; --white:#fff;
            --font-d:'Fraunces',serif; --font-b:'Plus Jakarta Sans',sans-serif;
            --shadow:0 1px 3px rgba(56,44,35,.06),0 4px 16px rgba(56,44,35,.07);
        }
        body { font-family:var(--font-b); background:var(--bg); color:var(--charcoal); display:flex; min-height:100vh; }

        /* Sidebar — sama dengan dashboard merchant */
        .sidebar { width:230px; min-height:100vh; background:var(--charcoal); display:flex; flex-direction:column; position:fixed; top:0; left:0; z-index:50; }
        .sb-brand { padding:1.4rem 1.4rem 1rem; border-bottom:1px solid rgba(254,250,224,.07); }
        .sb-brand-name { font-family:var(--font-d); font-size:1.2rem; color:var(--cornsilk); }
        .sb-role { font-size:.65rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--camel); margin-top:.2rem; }
        .sb-user { padding:1rem 1.4rem; border-bottom:1px solid rgba(254,250,224,.07); }
        .sb-name { font-size:.83rem; font-weight:600; color:var(--cornsilk); }
        .sb-sub  { font-size:.71rem; color:var(--camel); margin-top:.2rem; }
        nav.sb-nav { flex:1; padding:.75rem 0; }
        .nav-label { font-size:.61rem; font-weight:700; letter-spacing:.13em; text-transform:uppercase; color:rgba(254,250,224,.27); padding:.65rem 1.4rem .2rem; }
        .nav-item { display:flex; align-items:center; gap:.65rem; padding:.55rem 1.4rem; font-size:.8rem; color:rgba(254,250,224,.55); text-decoration:none; border-left:2.5px solid transparent; transition:all .15s; }
        .nav-item:hover { color:var(--cornsilk); background:rgba(254,250,224,.04); }
        .nav-item.active { color:var(--cornsilk); border-left-color:var(--camel); background:rgba(185,148,112,.12); font-weight:600; }
        .sb-footer { padding:1rem 1.4rem; border-top:1px solid rgba(254,250,224,.07); }
        .btn-logout { width:100%; padding:.55rem; background:transparent; border:1.5px solid rgba(254,250,224,.13); color:rgba(254,250,224,.45); border-radius:8px; font-family:var(--font-b); font-size:.75rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-logout:hover { border-color:rgba(254,250,224,.4); color:var(--cornsilk); }

        .main { margin-left:230px; flex:1; padding:2rem 2.25rem 4rem; }
        .topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; gap:1rem; }
        .page-title { font-family:var(--font-d); font-size:1.55rem; color:var(--charcoal); }
        .page-sub { font-size:.82rem; color:var(--camel); margin-top:.2rem; }
        .btn-primary { display:inline-flex; align-items:center; gap:.45rem; padding:.6rem 1.2rem; background:var(--camel); color:white; border:none; border-radius:10px; font-family:var(--font-b); font-weight:700; font-size:.83rem; cursor:pointer; transition:background .2s; text-decoration:none; }
        .btn-primary:hover { background:var(--charcoal); }

        .flash { padding:.75rem 1rem; border-radius:10px; font-size:.83rem; margin-bottom:1.25rem; }
        .flash-success { background:rgba(95,111,82,.1); border:1px solid rgba(95,111,82,.25); color:var(--olive); }
        .flash-error   { background:rgba(180,60,60,.08); border:1px solid rgba(180,60,60,.2); color:#a03030; }

        /* Grid kartu */
        .listing-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(230px, 1fr)); gap:1.25rem; }
        .lcard { background:var(--white); border-radius:14px; overflow:hidden; box-shadow:var(--shadow); transition:transform .2s, box-shadow .2s; }
        .lcard:hover { transform:translateY(-3px); box-shadow:0 6px 24px rgba(56,44,35,.11); }
        .lcard-thumb { height:130px; background:linear-gradient(135deg, var(--laurel), var(--olive)); display:flex; align-items:center; justify-content:center; font-size:2.5rem; position:relative; overflow:hidden; }
        .lcard-thumb img { width:100%; height:100%; object-fit:cover; }
        .lcard-status-tag { position:absolute; top:.5rem; left:.5rem; font-size:.63rem; font-weight:700; padding:.2rem .5rem; border-radius:6px; }
        .st-available   { background:var(--olive); color:white; }
        .st-unavailable { background:rgba(56,44,35,.6); color:white; }
        .st-sold_out    { background:#a03030; color:white; }
        .discount-tag { position:absolute; top:.5rem; right:.5rem; background:var(--camel); color:white; font-size:.63rem; font-weight:700; padding:.2rem .5rem; border-radius:6px; }
        .lcard-body { padding:.9rem 1rem 1rem; }
        .lcard-cat  { font-size:.67rem; color:var(--camel); font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:.25rem; }
        .lcard-name { font-size:.9rem; font-weight:600; color:var(--charcoal); margin-bottom:.4rem; line-height:1.3; }
        .lcard-prices { display:flex; align-items:baseline; gap:.4rem; margin-bottom:.3rem; }
        .price-new { font-family:var(--font-d); font-size:1rem; color:var(--olive); }
        .price-old { font-size:.73rem; color:#bbb; text-decoration:line-through; }
        .lcard-meta { font-size:.72rem; color:var(--camel); display:flex; gap:.75rem; margin-bottom:.85rem; }
        .lcard-actions { display:flex; gap:.5rem; }
        .btn-edit { flex:1; padding:.4rem; background:rgba(95,111,82,.08); color:var(--olive); border:1.5px solid rgba(95,111,82,.2); border-radius:7px; font-family:var(--font-b); font-size:.75rem; font-weight:600; cursor:pointer; text-align:center; text-decoration:none; transition:all .15s; }
        .btn-edit:hover { background:rgba(95,111,82,.15); }
        .btn-del { padding:.4rem .6rem; background:transparent; color:#a03030; border:1.5px solid rgba(180,60,60,.25); border-radius:7px; font-family:var(--font-b); font-size:.75rem; font-weight:600; cursor:pointer; transition:all .15s; }
        .btn-del:hover { background:rgba(180,60,60,.07); }

        .empty { text-align:center; padding:5rem 1rem; color:var(--camel); background:var(--white); border-radius:14px; box-shadow:var(--shadow); }
        .empty .ei { font-size:3rem; margin-bottom:.75rem; opacity:.4; }
        .empty p { font-size:.9rem; line-height:1.6; }

        .pager-row { display:flex; justify-content:center; margin-top:1.75rem; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-brand-name">🌿 EcoEats</div>
        <div class="sb-role">Portal Merchant</div>
    </div>
    <div class="sb-user">
        <div class="sb-name">{{ auth()->user()->name }}</div>
        <div class="sb-sub">{{ $profile?->business_name ?? 'Profil belum diisi' }}</div>
    </div>
    <nav class="sb-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('merchant.dashboard') }}" class="nav-item">📊 Dashboard</a>
        <a href="{{ route('merchant.listings.index') }}" class="nav-item active">🍱 Menu Surplus</a>
        <div class="nav-label">Akun</div>
        <a href="{{ route('merchant.profile.edit') }}" class="nav-item">🏪 Profil Usaha</a>
        <div class="nav-label">Segera Hadir</div>
        <span class="nav-item" style="opacity:.35; cursor:default;">📋 Pesanan Masuk</span>
    </nav>
    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf <button type="submit" class="btn-logout">↩ Keluar</button>
        </form>
    </div>
</aside>

<main class="main">
    @if(session('success'))<div class="flash flash-success">✅ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="flash flash-error">⚠️ {{ session('error') }}</div>@endif

    <div class="topbar">
        <div>
            <div class="page-title">Menu Surplus</div>
            <div class="page-sub">Kelola semua listing makanan surplus Anda</div>
        </div>
        @if($profile?->isApproved())
        <a href="{{ route('merchant.listings.create') }}" class="btn-primary">＋ Tambah Menu</a>
        @endif
    </div>

    @if($listings->isEmpty())
    <div class="empty">
        <div class="ei">🍱</div>
        <p>Belum ada menu surplus.<br>
        @if($profile?->isApproved())
            Klik "Tambah Menu" untuk mulai berjualan.
        @else
            Tunggu verifikasi akun disetujui admin.
        @endif
        </p>
    </div>
    @else
    <div class="listing-grid">
        @foreach($listings as $listing)
        <div class="lcard">
            <div class="lcard-thumb">
                @if($listing->photo_url)
                    <img src="{{ asset('storage/'.$listing->photo_url) }}" alt="{{ $listing->name }}">
                @else
                    🍽
                @endif
                <span class="lcard-status-tag st-{{ $listing->status }}">
                    {{ match($listing->status) { 'available'=>'Tersedia','sold_out'=>'Habis',default=>'Nonaktif' } }}
                </span>
                @php $disc = $listing->discountPercent(); @endphp
                @if($disc > 0)
                <span class="discount-tag">-{{ $disc }}%</span>
                @endif
            </div>
            <div class="lcard-body">
                <div class="lcard-cat">{{ $listing->category->name ?? '—' }}</div>
                <div class="lcard-name">{{ $listing->name }}</div>
                <div class="lcard-prices">
                    <span class="price-new">Rp {{ number_format($listing->discount_price, 0, ',', '.') }}</span>
                    <span class="price-old">Rp {{ number_format($listing->original_price, 0, ',', '.') }}</span>
                </div>
                <div class="lcard-meta">
                    <span>Stok: {{ $listing->stock_qty }}</span>
                    @if($listing->pickup_end)
                    <span>Sampai: {{ $listing->pickup_end->format('H:i') }}</span>
                    @endif
                </div>
                <div class="lcard-actions">
                    <a href="{{ route('merchant.listings.edit', $listing) }}" class="btn-edit">Edit</a>
                    <form method="POST" action="{{ route('merchant.listings.destroy', $listing) }}"
                          onsubmit="return confirm('Hapus \'{{ addslashes($listing->name) }}\'?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del">🗑</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if($listings->hasPages())
    <div class="pager-row">{{ $listings->links() }}</div>
    @endif
    @endif
</main>

</body>
</html>