@extends('layouts.merchant')

@section('title', 'Lokasi Usaha — EcoEats Merchant')
@section('active_nav', 'merchant.map')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .back-link { display:inline-flex; align-items:center; gap:.4rem; color:var(--camel); font-size:.8rem; font-weight:600; text-decoration:none; margin-bottom:1.25rem; transition:color .15s; }
    .back-link:hover { color:var(--olive); }

    .pg-head { margin-bottom:1.75rem; }
    .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
    .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

    .grid { display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start; }

    .map-panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:visible; }
    .map-panel-head { padding:1rem 1.25rem; border-bottom:1px solid rgba(56,44,35,.06); display:flex; align-items:center; justify-content:space-between; }
    .map-panel-title { font-family:var(--font-d); font-size:1rem; }
    #map { height:420px; width:100%; }

    .info-panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
    .info-head { padding:1rem 1.25rem; border-bottom:1px solid rgba(56,44,35,.06); }
    .info-title { font-family:var(--font-d); font-size:1rem; }
    .info-body { padding:1.25rem; }
    .info-row { padding:.6rem 0; border-bottom:1px solid rgba(56,44,35,.05); display:flex; flex-direction:column; gap:.2rem; }
    .info-row:last-child { border-bottom:none; }
    .info-lbl { font-size:.72rem; font-weight:600; color:var(--camel); text-transform:uppercase; letter-spacing:.04em; }
    .info-val { font-size:.84rem; color:var(--charcoal); line-height:1.5; }

    .coords-box { background:rgba(56,44,35,.04); border:1.5px solid rgba(56,44,35,.09); border-radius:9px; padding:.75rem 1rem; margin-top:1rem; }
    .coords-label { font-size:.68rem; font-weight:700; color:var(--camel); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.4rem; }
    .coords-val { font-family:monospace; font-size:.82rem; color:var(--charcoal); }

    .no-coords {
        background:rgba(185,148,112,.08); border:1.5px solid rgba(185,148,112,.25);
        border-radius:10px; padding:.85rem 1rem; font-size:.79rem; color:var(--camel);
        display:flex; gap:.5rem; align-items:flex-start; margin-bottom:1.25rem;
    }

    .edit-link-btn {
        display:block; text-align:center; margin-top:1rem;
        padding:.6rem; background:rgba(95,111,82,.08);
        border:1.5px solid rgba(95,111,82,.2); border-radius:9px;
        font-size:.8rem; font-weight:600; color:var(--olive); text-decoration:none;
        transition:all .15s;
    }
    .edit-link-btn:hover { background:rgba(95,111,82,.15); }

    @media(max-width:900px) { .grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')
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
@endsection

@section('scripts')
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
    map.setView([-7.5695, 110.8270], 13);
}
</script>
@endsection