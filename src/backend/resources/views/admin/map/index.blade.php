{{--
    resources/views/admin/map/index.blade.php
    Data dari: Admin\MapController@index
    Variabel: $merchants (Collection of arrays)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Merchant — EcoEats Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#eee9df; --white:#fff;
            --font-d:'Fraunces',serif; --font-b:'Plus Jakarta Sans',sans-serif;
            --shadow:0 1px 3px rgba(56,44,35,.06),0 4px 16px rgba(56,44,35,.07);
        }
        body { font-family:var(--font-b); background:var(--bg); color:var(--charcoal); min-height:100vh; }

        header { background:var(--charcoal); height:58px; display:flex; align-items:center; padding:0 1.75rem; gap:1.25rem; position:sticky; top:0; z-index:1000; }
        .h-brand { font-family:var(--font-d); font-size:1.15rem; color:var(--cornsilk); }
        .h-sep { width:1px; height:18px; background:rgba(254,250,224,.12); }
        .h-label { font-size:.68rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--camel); }
        .h-right { margin-left:auto; display:flex; align-items:center; gap:1rem; }
        .h-user { font-size:.8rem; color:rgba(254,250,224,.65); }
        .h-user strong { color:var(--cornsilk); }
        .btn-out { padding:.32rem .8rem; background:transparent; border:1.5px solid rgba(254,250,224,.18); color:rgba(254,250,224,.55); border-radius:999px; font-family:var(--font-b); font-size:.73rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-out:hover { border-color:rgba(254,250,224,.45); color:var(--cornsilk); }

        .layout { display:flex; min-height:calc(100vh - 58px); }

        .sidenav { width:210px; background:var(--white); border-right:1px solid rgba(56,44,35,.07); padding:1.25rem 0; position:sticky; top:58px; height:calc(100vh - 58px); overflow-y:auto; flex-shrink:0; z-index:500; }
        .sn-label { font-size:.6rem; font-weight:700; letter-spacing:.13em; text-transform:uppercase; color:rgba(56,44,35,.28); padding:.65rem 1.25rem .2rem; }
        .sn-item { display:flex; align-items:center; gap:.6rem; padding:.52rem 1.25rem; font-size:.8rem; color:rgba(56,44,35,.5); text-decoration:none; border-left:2.5px solid transparent; transition:all .15s; }
        .sn-item:hover { color:var(--charcoal); background:rgba(56,44,35,.025); }
        .sn-item.active { color:var(--charcoal); border-left-color:var(--olive); background:rgba(95,111,82,.06); font-weight:600; }

       .content { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            overflow-x: hidden; /* Tambahkan ini untuk memotong sidebar yang tersembunyi */
        }

        /* Page header */
        .pg-bar {
            padding:1.25rem 2rem;
            display:flex; align-items:center; justify-content:space-between; gap:1rem;
            border-bottom:1px solid rgba(56,44,35,.08);
            background:var(--white);
        }
        .pg-title { font-family:var(--font-d); font-size:1.3rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.15rem; }

        /* Stats strip */
        .stats-strip {
            display:flex; gap:1rem; padding:1rem 2rem;
            background:var(--bg); border-bottom:1px solid rgba(56,44,35,.07);
            flex-wrap:wrap;
        }
        .stat-pill {
            display:inline-flex; align-items:center; gap:.5rem;
            background:var(--white); border-radius:999px;
            padding:.35rem 1rem; font-size:.78rem; font-weight:600;
            box-shadow:var(--shadow);
        }
        .stat-pill .dot { width:8px; height:8px; border-radius:50%; }
        .dot-olive  { background:var(--olive); }
        .dot-camel  { background:var(--camel); }

        /* Search bar */
        .search-bar {
            padding:.75rem 2rem;
            background:var(--white);
            border-bottom:1px solid rgba(56,44,35,.07);
        }
        .search-input {
            width:100%; max-width:360px;
            padding:.5rem .9rem; font-family:var(--font-b); font-size:.83rem;
            color:var(--charcoal); background:var(--bg);
            border:1.5px solid rgba(56,44,35,.12); border-radius:9px; outline:none;
            transition:border-color .2s;
        }
        .search-input:focus { border-color:var(--laurel); background:white; }

        /* Map container */
#map { height: 600px; width: 100%; }

        /* Merchant list panel (slide-in) */
        .merchant-list {
            position:absolute; right:0; top:58px;
            width:300px; height:calc(100vh - 58px);
            background:var(--white);
            box-shadow:-4px 0 20px rgba(56,44,35,.1);
            z-index:600;
            display:flex; flex-direction:column;
            transform:translateX(100%);
            transition:transform .3s ease;
        }
        .merchant-list.open { transform:translateX(0); }
        .ml-head {
            padding:1rem 1.25rem;
            border-bottom:1px solid rgba(56,44,35,.07);
            display:flex; align-items:center; justify-content:space-between;
        }
        .ml-title { font-family:var(--font-d); font-size:.95rem; }
        .ml-close { background:transparent; border:none; font-size:1.1rem; cursor:pointer; color:var(--camel); }
        .ml-body { flex:1; overflow-y:auto; padding:.5rem 0; }
        .ml-item {
            display:flex; align-items:flex-start; gap:.75rem;
            padding:.75rem 1.25rem; cursor:pointer;
            border-left:3px solid transparent;
            transition:all .15s;
        }
        .ml-item:hover { background:rgba(56,44,35,.025); }
        .ml-item.focused { border-left-color:var(--olive); background:rgba(95,111,82,.05); }
        .ml-icon { width:32px; height:32px; border-radius:8px; background:rgba(95,111,82,.1); display:flex; align-items:center; justify-content:center; font-size:.9rem; flex-shrink:0; }
        .ml-name { font-size:.82rem; font-weight:600; color:var(--charcoal); }
        .ml-addr { font-size:.72rem; color:var(--camel); margin-top:.1rem; line-height:1.4; }
        .ml-badge { font-size:.67rem; font-weight:700; color:var(--olive); background:rgba(95,111,82,.1); padding:.1rem .45rem; border-radius:999px; margin-top:.3rem; display:inline-block; }

        /* Toggle list button */
        .btn-list {
            display:inline-flex; align-items:center; gap:.4rem;
            padding:.4rem .9rem; background:var(--white);
            border:1.5px solid rgba(56,44,35,.15); border-radius:9px;
            font-family:var(--font-b); font-size:.78rem; font-weight:600;
            color:var(--charcoal); cursor:pointer; transition:all .15s;
        }
        .btn-list:hover { border-color:var(--olive); color:var(--olive); }

        /* Leaflet popup custom */
        .popup-content { font-family:var(--font-b); min-width:200px; }
        .popup-name { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); margin-bottom:.3rem; }
        .popup-addr { font-size:.76rem; color:var(--camel); margin-bottom:.5rem; line-height:1.4; }
        .popup-badge { font-size:.7rem; font-weight:700; padding:.18rem .55rem; border-radius:999px; background:rgba(95,111,82,.12); color:var(--olive); }
        .popup-link { display:inline-block; margin-top:.6rem; font-size:.75rem; font-weight:600; color:var(--olive); text-decoration:none; }
        .popup-link:hover { text-decoration:underline; }
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
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
        <a href="{{ route('admin.map') }}" class="sn-item active">🗺 Peta Merchant</a>
    </aside>

    <div class="content" style="position:relative;">

        <div class="pg-bar">
            <div>
                <div class="pg-title">Peta Merchant</div>
                <div class="pg-crumb">EcoEats › Admin › Peta Merchant</div>
            </div>
            <button class="btn-list" onclick="toggleList()">📋 Daftar Merchant</button>
        </div>

        <div class="stats-strip">
            <div class="stat-pill">
                <span class="dot dot-olive"></span>
                {{ $merchants->count() }} merchant aktif di peta
            </div>
            <div class="stat-pill">
                <span class="dot dot-camel"></span>
                {{ $merchants->sum('active_listings') }} listing tersedia
            </div>
        </div>

        <div class="search-bar">
            <input class="search-input" type="text" id="searchInput"
                   placeholder="Cari nama merchant atau alamat…">
        </div>

        <div id="map"></div>

        {{-- Merchant list panel --}}
        <div class="merchant-list" id="merchantList">
            <div class="ml-head">
                <div class="ml-title">Daftar Merchant</div>
                <button class="ml-close" onclick="toggleList()">✕</button>
            </div>
            <div class="ml-body" id="merchantListBody">
                @foreach($merchants as $m)
                <div class="ml-item" id="list-{{ $m['id'] }}"
                     onclick="focusMerchant({{ $m['id'] }}, {{ $m['latitude'] }}, {{ $m['longitude'] }})">
                    <div class="ml-icon">🏪</div>
                    <div>
                        <div class="ml-name">{{ $m['business_name'] }}</div>
                        <div class="ml-addr">{{ Str::limit($m['business_address'], 60) }}</div>
                        <span class="ml-badge">{{ $m['active_listings'] }} listing</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
// Data merchant dari controller — di-encode ke JSON aman
const merchants = @json($merchants);

// Inisialisasi peta — center di Kota Solo
const map = L.map('map').setView([-7.5695, 110.8270], 13);

// Tile layer OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19,
}).addTo(map);

// Custom marker icon pakai warna brand
const markerIcon = L.divIcon({
    className: '',
    html: `<div style="
        width:32px; height:32px; border-radius:50% 50% 50% 0;
        background:#5F6F52; border:3px solid white;
        box-shadow:0 2px 8px rgba(56,44,35,.35);
        transform:rotate(-45deg);
        display:flex; align-items:center; justify-content:center;
    "><span style="transform:rotate(45deg); font-size:12px;">🏪</span></div>`,
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -36],
});

// Map untuk akses marker by id
const markerMap = {};

merchants.forEach(m => {
    const marker = L.marker([m.latitude, m.longitude], { icon: markerIcon })
        .addTo(map)
        .bindPopup(`
            <div class="popup-content">
                <div class="popup-name">${m.business_name}</div>
                <div class="popup-addr">${m.business_address}</div>
                <span class="popup-badge">${m.active_listings} listing aktif</span><br>
                <a class="popup-link" href="/admin/merchants/${m.id}">Lihat detail merchant →</a>
            </div>
        `, { maxWidth: 260 });

    markerMap[m.id] = marker;
});

// Fit bounds jika ada marker
if (merchants.length > 0) {
    const group = L.featureGroup(Object.values(markerMap));
    map.fitBounds(group.getBounds().pad(0.15));
}

// Focus ke merchant dari list
function focusMerchant(id, lat, lng) {
    map.setView([lat, lng], 16, { animate: true });
    if (markerMap[id]) markerMap[id].openPopup();

    // Highlight item di list
    document.querySelectorAll('.ml-item').forEach(el => el.classList.remove('focused'));
    const item = document.getElementById('list-' + id);
    if (item) item.classList.add('focused');
}

// Toggle list panel
function toggleList() {
    document.getElementById('merchantList').classList.toggle('open');
}

// Search filter list
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.ml-item').forEach(el => {
        const text = el.innerText.toLowerCase();
        el.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>