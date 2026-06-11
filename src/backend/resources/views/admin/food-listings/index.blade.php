{{--
    resources/views/admin/food-listings/index.blade.php
    Data dari: App\Http\Controllers\Admin\FoodListingController@index
    Variabel: $listings (Paginator), $categories (Collection), $search, $status, $category
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Listing — EcoEats Admin</title>
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

        .toolbar { display:flex; align-items:center; gap:.75rem; margin-bottom:1.25rem; flex-wrap:wrap; }
        .search-wrap { display:flex; }
        .search-input { padding:.55rem .9rem; font-family:var(--font-b); font-size:.83rem; color:var(--charcoal); background:var(--white); border:1.5px solid rgba(56,44,35,.12); border-radius:9px 0 0 9px; outline:none; min-width:220px; transition:border-color .2s; }
        .search-input:focus { border-color:var(--laurel); }
        .select-filter { padding:.55rem .9rem; font-family:var(--font-b); font-size:.83rem; color:var(--charcoal); background:var(--white); border:1.5px solid rgba(56,44,35,.12); border-left:none; outline:none; cursor:pointer; }
        .search-btn { padding:.55rem .9rem; background:var(--olive); color:white; border:none; border-radius:0 9px 9px 0; font-family:var(--font-b); font-size:.83rem; font-weight:600; cursor:pointer; transition:background .2s; }
        .search-btn:hover { background:var(--charcoal); }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        thead tr { background:rgba(56,44,35,.025); }
        th { text-align:left; padding:.6rem 1.25rem; font-size:.67rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:rgba(56,44,35,.38); white-space:nowrap; }
        td { padding:.78rem 1.25rem; font-size:.82rem; color:var(--charcoal); border-top:1px solid rgba(56,44,35,.05); vertical-align:middle; }
        tr:hover td { background:rgba(56,44,35,.012); }

        .listing-thumb { width:40px; height:40px; border-radius:8px; background:var(--laurel); object-fit:cover; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:1.2rem; }
        .listing-info { display:flex; align-items:center; gap:.7rem; }
        .listing-name { font-weight:600; }
        .listing-cat  { font-size:.72rem; color:var(--camel); margin-top:.1rem; }

        .badge { display:inline-block; padding:.18rem .6rem; border-radius:999px; font-size:.67rem; font-weight:700; }
        .b-available   { background:rgba(95,111,82,.12); color:var(--olive); }
        .b-unavailable { background:rgba(56,44,35,.07); color:rgba(56,44,35,.5); }
        .b-sold_out    { background:rgba(180,60,60,.08); color:#a03030; }

        .price-new { font-family:var(--font-d); font-size:.95rem; color:var(--olive); }
        .price-old { font-size:.72rem; color:#bbb; text-decoration:line-through; display:block; }

        .btn-sm { padding:.25rem .65rem; border-radius:6px; font-family:var(--font-b); font-size:.72rem; font-weight:600; cursor:pointer; transition:all .15s; border:1.5px solid transparent; }
        .btn-del { background:transparent; color:#a03030; border-color:rgba(180,60,60,.25); }
        .btn-del:hover { background:rgba(180,60,60,.06); }

        .empty { text-align:center; padding:3rem; color:var(--camel); }
        .empty .ei { font-size:2rem; margin-bottom:.6rem; opacity:.4; }
        .empty p { font-size:.82rem; }

        .pager { display:flex; justify-content:flex-end; padding:1rem 1.25rem; border-top:1px solid rgba(56,44,35,.06); }
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
        <a href="{{ route('admin.food-listings.index') }}" class="sn-item active">🍱 Food Listing</a>
        <a href="{{ route('admin.orders.index') }}" class="sn-item">📋 Semua Pesanan</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
    </aside>

    <main class="content">
        @if(session('success'))<div class="flash flash-success">✅ {{ session('success') }}</div>@endif

        <div class="pg-head">
            <h1>Manajemen Food Listing</h1>
            <div class="pg-crumb">EcoEats › Admin › Food Listing</div>
        </div>

        <div class="toolbar">
            <form method="GET" action="{{ route('admin.food-listings.index') }}" class="search-wrap">
                <input class="search-input" type="text" name="search"
                       value="{{ $search }}" placeholder="Cari nama atau merchant…">
                <select class="select-filter" name="status">
                    <option value="all"        {{ $status==='all'         ? 'selected' : '' }}>Semua Status</option>
                    <option value="available"  {{ $status==='available'   ? 'selected' : '' }}>Available</option>
                    <option value="unavailable"{{ $status==='unavailable' ? 'selected' : '' }}>Unavailable</option>
                    <option value="sold_out"   {{ $status==='sold_out'    ? 'selected' : '' }}>Sold Out</option>
                </select>
                <select class="select-filter" name="category" style="border-radius:0">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="search-btn">Filter</button>
            </form>
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Merchant</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Pickup</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($listings as $listing)
                    <tr>
                        <td>
                            <div class="listing-info">
                                <div class="listing-thumb">
                                    @if($listing->photo_url)
                                        <img src="{{ asset('storage/'.$listing->photo_url) }}"
                                             style="width:40px;height:40px;border-radius:8px;object-fit:cover"
                                             alt="{{ $listing->name }}">
                                    @else
                                        🍱
                                    @endif
                                </div>
                                <div>
                                    <div class="listing-name">{{ $listing->name }}</div>
                                    <div class="listing-cat">{{ $listing->category->name ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600">{{ $listing->merchant->business_name ?? '—' }}</div>
                            <div style="font-size:.73rem; color:var(--camel)">{{ $listing->merchant->user->name ?? '' }}</div>
                        </td>
                        <td>
                            <span class="price-new">Rp {{ number_format($listing->discount_price, 0, ',', '.') }}</span>
                            <span class="price-old">Rp {{ number_format($listing->original_price, 0, ',', '.') }}</span>
                        </td>
                        <td>{{ $listing->stock_qty }}</td>
                        <td style="font-size:.75rem; color:var(--camel)">
                            @if($listing->pickup_start && $listing->pickup_end)
                                {{ $listing->pickup_start->format('d M H:i') }}–{{ $listing->pickup_end->format('H:i') }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <span class="badge b-{{ $listing->status }}">
                                {{ match($listing->status) {
                                    'available'   => 'Available',
                                    'unavailable' => 'Unavailable',
                                    'sold_out'    => 'Sold Out',
                                } }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.food-listings.destroy', $listing) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus listing \'{{ addslashes($listing->name) }}\'? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-del">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <div class="ei">🍱</div>
                                <p>Tidak ada listing ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($listings->hasPages())
            <div class="pager">{{ $listings->links() }}</div>
            @endif
        </div>
    </main>
</div>
</body>
</html>