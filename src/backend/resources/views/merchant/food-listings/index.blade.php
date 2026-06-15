@extends('layouts.merchant')

@section('title', 'Menu Surplus')
@section('active_nav', 'merchant.listings')

@section('styles')
<style>
    .topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; gap:1rem; }
    .page-title { font-family:var(--font-d); font-size:1.55rem; color:var(--charcoal); }
    .page-sub { font-size:.82rem; color:var(--camel); margin-top:.2rem; }

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
@endsection

@section('content')

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
            <div class="lcard-cat">{{ $listing->category?->name ?? '—' }}</div>
            <div class="lcard-name">{{ $listing->name }}</div>
            <div class="lcard-prices">
                <span class="price-new">Rp{{ number_format($listing->discount_price, 0, ',', '.') }}</span>
                <span class="price-old">Rp{{ number_format($listing->original_price, 0, ',', '.') }}</span>
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

@endsection