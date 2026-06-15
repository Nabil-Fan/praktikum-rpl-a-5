{{--
    resources/views/merchant/map/index.blade.php
    Data dari: Merchant\MapController@index
    Variabel: $profile (MerchantProfile), $location (array)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Usaha — EcoEats Merchant</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#f2ede4; --white:#fff;
            --font-d:'Fraunces',serif; --font-b:'Plus Jakarta Sans',sans-serif;
            --shadow:0 1px 3px rgba(56,44,35,.06),0 4px 16px rgba(56,44,35,.07);
        }
        body { font-family:var(--font-b); background:var(--bg); color:var(--charcoal); display:flex; min-height:100vh; }

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

        .back-link { display:inline-flex; align-items:center; gap:.4rem; color:var(--camel); font-size:.8rem; font-weight:600; text-decoration:none; margin-bottom:1.25rem; transition:color .15s; }
        .back-link:hover { color:var(--olive); }

        .pg-head { margin-bottom:1.75rem; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        .grid { display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start; }

        /* Map panel */
        .map-panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:visible; }
        .map-panel-head { padding:1rem 1.25rem; border-bottom:1px solid rgba(56,44,35,.06); display:flex; align-items:center; justify-content:space-between; }
        .map-panel-title { font-family:var(--font-d); font-size:1rem; }
        #map { height:420px; width:100%; }

        /* Info panel */
        .info-panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
        .info-head { padding:1rem 1.25rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .info-title { font-family:var(--font-d); font-size:1rem; }
        .info-body { padding:1.25rem; }
        .info-row { padding:.6rem 0; border-bottom:1px solid rgba(56,44,35,.05); display:flex; flex-direction:column; gap:.2rem; }
        .info-row:last-child { border-bottom:none; }
        .info-lbl { font-size:.72rem; font-weight:600; color:var(--camel); text-transform:uppercase; letter-spacing:.04em; }
        .info-val { font-size:.84rem; color:var(--charcoal); line-height:1.5; }

        /* Coords highlight */
        .coords-box { background:rgba(56,44,35,.04); border:1.5px solid rgba(56,44,35,.09); border-radius:9px; padding:.75rem 1rem; margin-top:1rem; }
        .coords-label { font-size:.68rem; font-weight:700; color:var(--camel); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.4rem; }
        .coords-val { font-family:monospace; font-size:.82rem; color:var(--charcoal); }

        /* No coords warning */
        .no-coords {
            background:rgba(185,148,112,.08); border:1.5px solid rgba(185,148,112,.25);
            border-radius:10px; padding:.85rem 1rem; font-size:.79rem; color:var(--camel);
            display:flex; gap:.5rem; align-items:flex-start; margin-bottom:1.25rem;
        }

        /* Edit link */
        .edit-link-btn {
            display:block; text-align:center; margin-top:1rem;
            padding:.6rem; background:rgba(95,111,82,.08);
            border:1.5px solid rgba(95,111,82,.2); border-radius:9px;
            font-size:.8rem; font-weight:600; color:var(--olive); text-decoration:none;
            transition:all .15s;
        }
        .edit-link-btn:hover { background:rgba(95,111,82,.15); }

        @media(max-width:900px) { .grid { grid-template-columns:1fr; } .sidebar { display:none; } .main { margin-left:0; } }
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
        <div class="sb-sub">{{ $profile->business_name }}</div>
    </div>
    <nav class="sb-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('merchant.dashboard') }}" class="nav-item">📊 Dashboard</a>
        <a href="{{ route('merchant.listings.index') }}" class="nav-item">🍱 Menu Surplus</a>
        <a href="{{ route('merchant.orders.index') }}" class="nav-item">📋 Pesanan Masuk</a>
        <div class="nav-label">Keuangan</div>
        <a href="{{ route('merchant.withdrawals.index') }}" class="nav-item">💰 Penarikan Dana</a>
        <div class="nav-label">Akun</div>
        <a href="{{ route('merchant.profile.edit') }}" class="nav-item">🏪 Profil Usaha</a>
        <a href="{{ route('merchant.map') }}" class="nav-item active">🗺 Lokasi Usaha</a>
    </nav>
    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf <button type="submit" class="btn-logout">↩ Keluar</button>
        </form>
    </div>
</aside>

<main class="main">

    <a href="{{ route('merchant.dashboard') }}" class="back-link">← Kembali ke Dashboard</a>

    <div class="pg-head">
        <h1>Lokasi Usaha</h1>
        <div class="pg-crumb">EcoEats › Merchant › Lokasi Usaha</div>
    </div>

    @if(!$location['has_coords'])
    <div class="no-coords">
        <span>⚠️</span>
        <span>Koordinat lokasi usaha belum diisi. Perbarui profil usaha Anda agar lokasi muncul di peta.</span>
    </div>
    @endif

    <div class="grid">

        {{-- Peta --}}
        <div class="map-panel">
            <div class="map-panel-head">
                <div class="map-panel-title">{{ $profile->business_name }}</div>
                @if($location['has_coords'])
                    <span style="font-size:.72rem; color:var(--olive); font-weight:600">📍 Koordinat tersimpan</span>
                @else
                    <span style="font-size:.72rem; color:var(--camel)">Koordinat belum diisi</span>
                @endif
            </div>
            <div id="map"></div>
        </div>

        {{-- Info & koordinat --}}
        <div class="info-panel">
            <div class="info-head"><div class="info-title">Info Lokasi</div></div>
            <div class="info-body">
                <div class="info-row">
                    <div class="info-lbl">Nama Usaha</div>
                    <div class="info-val">{{ $profile->business_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-lbl">Alamat</div>
                    <div class="info-val">{{ $profile->business_address }}</div>
                </div>

                @if($location['has_coords'])
                <div class="coords-box">
                    <div class="coords-label">Koordinat</div>
                    <div class="coords-val">{{ $profile->latitude }}, {{ $profile->longitude }}</div>
                </div>
                @endif

                <a href="{{ route('merchant.profile.edit') }}" class="edit-link-btn">
                    ✏️ Edit Koordinat di Profil
                </a>
            </div>
        </div>

    </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
const lat = {{ $location['latitude'] }};
const lng = {{ $location['longitude'] }};
const hasCoords = {{ $location['has_coords'] ? 'true' : 'false' }};
const businessName = @json($location['business_name']);

const map = L.map('map').setView([lat, lng], hasCoords ? 16 : 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19,
}).addTo(map);

if (hasCoords) {
    // Custom marker
    const icon = L.divIcon({
        className: '',
        html: `<div style="
            width:36px; height:36px; border-radius:50% 50% 50% 0;
            background:#5F6F52; border:3px solid white;
            box-shadow:0 3px 10px rgba(56,44,35,.4);
            transform:rotate(-45deg);
            display:flex; align-items:center; justify-content:center;
        "><span style="transform:rotate(45deg); font-size:14px;">🏪</span></div>`,
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        popupAnchor: [0, -40],
    });

    L.marker([lat, lng], { icon })
        .addTo(map)
        .bindPopup(`<b style="font-family:'Fraunces',serif">${businessName}</b>`, { maxWidth: 200 })
        .openPopup();
} else {
    // Tampilkan kota Solo saja tanpa marker
    map.setView([-7.5695, 110.8270], 13);
}
</script>
</body>
</html>