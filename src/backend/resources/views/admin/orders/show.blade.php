{{--
    resources/views/admin/orders/show.blade.php
    Data dari: Admin\OrderController@show
    Variabel: $order (Order, with user, merchant, items.listing, payments)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan {{ $order->pickup_code }} — EcoEats Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#edeae3; --white:#fff;
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

        .back-link { display:inline-flex; align-items:center; gap:.4rem; color:var(--camel); font-size:.8rem; font-weight:600; text-decoration:none; margin-bottom:1.25rem; transition:color .15s; }
        .back-link:hover { color:var(--olive); }

        .pg-head { margin-bottom:1.75rem; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        /* Status badge */
        .badge { display:inline-block; padding:.25rem .75rem; border-radius:999px; font-size:.75rem; font-weight:700; }
        .b-pending   { background:rgba(185,148,112,.12); color:#8a5c2a; }
        .b-confirmed { background:rgba(95,111,82,.12);   color:var(--olive); }
        .b-ready     { background:rgba(169,179,136,.2);  color:#3d5c2a; }
        .b-completed { background:rgba(95,111,82,.08);   color:var(--olive); }
        .b-rejected  { background:rgba(180,60,60,.08);   color:#a03030; }
        .b-expired   { background:rgba(56,44,35,.07);    color:rgba(56,44,35,.4); }

        /* Grid layout */
        .grid-main { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }

        /* Panel */
        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.25rem; }
        .panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); display:flex; align-items:center; justify-content:space-between; }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-body { padding:1.25rem 1.5rem; }

        /* Info rows */
        .info-row { display:flex; gap:.75rem; padding:.55rem 0; border-bottom:1px solid rgba(56,44,35,.05); align-items:flex-start; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:.73rem; font-weight:600; color:var(--camel); min-width:130px; flex-shrink:0; padding-top:.05rem; }
        .info-val { font-size:.83rem; color:var(--charcoal); line-height:1.5; }

        /* Pickup code highlight */
        .pickup-code-box {
            background:rgba(95,111,82,.06); border:2px solid rgba(95,111,82,.2);
            border-radius:12px; padding:1rem 1.25rem; text-align:center;
            margin-bottom:1.25rem;
        }
        .pickup-code-label { font-size:.68rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--camel); margin-bottom:.35rem; }
        .pickup-code-val { font-family:var(--font-d); font-size:2rem; letter-spacing:.15em; color:var(--olive); font-weight:700; }

        /* Order items table */
        table { width:100%; border-collapse:collapse; }
        thead tr { background:rgba(56,44,35,.025); }
        th { text-align:left; padding:.55rem 1rem; font-size:.67rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:rgba(56,44,35,.38); }
        td { padding:.75rem 1rem; font-size:.82rem; color:var(--charcoal); border-top:1px solid rgba(56,44,35,.05); vertical-align:middle; }
        .item-name { font-weight:600; }
        .item-deleted { color:rgba(56,44,35,.35); font-style:italic; font-size:.75rem; }
        .item-qty { color:var(--camel); font-size:.78rem; }
        .item-price { font-family:var(--font-d); color:var(--olive); }
        .item-subtotal { font-family:var(--font-d); font-weight:700; }
        .total-row td { border-top:2px solid rgba(56,44,35,.1); padding-top:.85rem; }

        /* Timeline */
        .timeline { display:flex; flex-direction:column; gap:0; }
        .tl-item { display:flex; gap:.85rem; align-items:flex-start; position:relative; padding-bottom:.9rem; }
        .tl-item:last-child { padding-bottom:0; }
        .tl-item:not(:last-child)::before {
            content:''; position:absolute;
            left:.65rem; top:1.3rem; bottom:0;
            width:1.5px; background:rgba(56,44,35,.1);
        }
        .tl-dot {
            width:1.3rem; height:1.3rem; border-radius:50%; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            font-size:.65rem; margin-top:.05rem;
        }
        .tl-done  { background:rgba(95,111,82,.15); color:var(--olive); }
        .tl-miss  { background:rgba(56,44,35,.06); color:rgba(56,44,35,.3); }
        .tl-reject{ background:rgba(180,60,60,.1); color:#a03030; }
        .tl-label { font-size:.8rem; font-weight:600; color:var(--charcoal); }
        .tl-time  { font-size:.72rem; color:var(--camel); margin-top:.1rem; }

        /* Payment record */
        .pay-row { display:flex; align-items:center; justify-content:space-between; padding:.6rem 0; border-bottom:1px solid rgba(56,44,35,.05); }
        .pay-row:last-child { border-bottom:none; }
        .pay-method { font-size:.78rem; font-weight:700; color:var(--charcoal); text-transform:uppercase; }
        .pay-status { font-size:.68rem; font-weight:700; padding:.18rem .55rem; border-radius:999px; }
        .ps-paid     { background:rgba(95,111,82,.12); color:var(--olive); }
        .ps-pending  { background:rgba(185,148,112,.12); color:#8a5c2a; }
        .ps-failed   { background:rgba(180,60,60,.08); color:#a03030; }
        .ps-refunded { background:rgba(56,44,35,.07); color:rgba(56,44,35,.5); }
        .ps-expired  { background:rgba(56,44,35,.07); color:rgba(56,44,35,.4); }
        .pay-amount  { font-family:var(--font-d); font-size:.95rem; color:var(--olive); }

        /* Admin note */
        .admin-note {
            background:rgba(169,179,136,.1); border:1.5px solid rgba(169,179,136,.25);
            border-radius:10px; padding:.85rem 1rem; font-size:.78rem; color:var(--olive);
            display:flex; gap:.5rem; align-items:flex-start;
        }

        @media(max-width:960px) { .grid-main { grid-template-columns:1fr; } .sidenav { display:none; } }
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
        <a href="{{ route('admin.orders.index') }}" class="sn-item active">📋 Semua Pesanan</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
    </aside>

    <main class="content">

        <a href="{{ route('admin.orders.index') }}" class="back-link">← Kembali ke Semua Pesanan</a>

        <div class="pg-head">
            <div>
                <h1>Detail Pesanan</h1>
                <div class="pg-crumb">EcoEats › Admin › Pesanan › {{ $order->pickup_code }}</div>
            </div>
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
        </div>

        <div class="admin-note" style="margin-bottom:1.5rem">
            <span>ℹ️</span>
            <span>Admin hanya bisa memonitor pesanan. Perubahan status dilakukan oleh merchant.</span>
        </div>

        <div class="grid-main">

            {{-- Kolom kiri: info pesanan + item + pembayaran --}}
            <div>

                {{-- Kode Pickup --}}
                <div class="pickup-code-box">
                    <div class="pickup-code-label">Kode Pickup</div>
                    <div class="pickup-code-val">{{ $order->pickup_code }}</div>
                </div>

                {{-- Info umum --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Informasi Pesanan</div></div>
                    <div class="panel-body">
                        <div class="info-row">
                            <div class="info-label">Pembeli</div>
                            <div class="info-val">
                                <div>{{ $order->user?->name ?? '—' }}</div>
                                <div style="font-size:.73rem; color:var(--camel)">{{ $order->user?->email }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Merchant</div>
                            <div class="info-val">
                                <div>{{ $order->merchant?->business_name ?? '—' }}</div>
                                <div style="font-size:.73rem; color:var(--camel)">{{ $order->merchant?->business_address }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Metode Bayar</div>
                            <div class="info-val" style="text-transform:uppercase; font-weight:600">
                                {{ $order->payment_method }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Waktu Pesan</div>
                            <div class="info-val">{{ $order->ordered_at->format('d M Y, H:i') }}</div>
                        </div>
                        @if($order->expires_at)
                        <div class="info-row">
                            <div class="info-label">Batas Konfirmasi</div>
                            <div class="info-val" style="{{ $order->isPending() && $order->expires_at->isPast() ? 'color:#a03030' : '' }}">
                                {{ $order->expires_at->format('d M Y, H:i') }}
                                @if($order->isPending() && $order->expires_at->isPast())
                                    <span style="font-size:.72rem">(sudah lewat)</span>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">Total</div>
                            <div class="info-val" style="font-family:var(--font-d); font-size:1.1rem; color:var(--olive)">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Item pesanan --}}
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-title">Item Pesanan</div>
                        <span style="font-size:.75rem; color:var(--camel)">{{ $order->items->count() }} item</span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga Satuan</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    {{-- Tampilkan snapshot nama, bukan dari relasi listing --}}
                                    <div class="item-name">{{ $item->listing_name }}</div>
                                    @if(!$item->listing || $item->listing->deleted_at)
                                        <div class="item-deleted">listing sudah dihapus</div>
                                    @endif
                                </td>
                                <td class="item-price">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="item-qty">× {{ $item->quantity }}</td>
                                <td class="item-subtotal">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="3" style="text-align:right; font-weight:600; color:var(--camel); font-size:.78rem; letter-spacing:.04em; text-transform:uppercase">Total</td>
                                <td style="font-family:var(--font-d); font-size:1.1rem; color:var(--olive); font-weight:700">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Riwayat pembayaran --}}
                @if($order->payments->isNotEmpty())
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Riwayat Pembayaran</div></div>
                    <div class="panel-body">
                        @foreach($order->payments as $pay)
                        <div class="pay-row">
                            <div>
                                <div class="pay-method">{{ $pay->payment_gateway }}</div>
                                @if($pay->transaction_id)
                                    <div style="font-size:.72rem; color:var(--camel)">ID: {{ $pay->transaction_id }}</div>
                                @endif
                                <div style="font-size:.72rem; color:var(--camel)">
                                    {{ $pay->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            <div style="text-align:right">
                                <div class="pay-amount">Rp{{ number_format($pay->amount, 0, ',', '.') }}</div>
                                <span class="pay-status ps-{{ $pay->status }}">{{ $pay->status }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- Kolom kanan: timeline status --}}
            <div>
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Timeline Status</div></div>
                    <div class="panel-body">
                        <div class="timeline">

                            {{-- Pesanan dibuat --}}
                            <div class="tl-item">
                                <div class="tl-dot tl-done">✓</div>
                                <div>
                                    <div class="tl-label">Pesanan Dibuat</div>
                                    <div class="tl-time">{{ $order->ordered_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>

                            {{-- Dikonfirmasi --}}
                            <div class="tl-item">
                                <div class="tl-dot {{ $order->confirmed_at ? 'tl-done' : ($order->isRejected() || $order->isExpired() ? 'tl-reject' : 'tl-miss') }}">
                                    {{ $order->confirmed_at ? '✓' : ($order->isRejected() ? '✕' : '○') }}
                                </div>
                                <div>
                                    <div class="tl-label" style="{{ !$order->confirmed_at ? 'color:rgba(56,44,35,.35)' : '' }}">
                                        Dikonfirmasi Merchant
                                    </div>
                                    @if($order->confirmed_at)
                                        <div class="tl-time">{{ $order->confirmed_at->format('d M Y, H:i') }}</div>
                                    @elseif($order->isRejected())
                                        <div class="tl-time" style="color:#a03030">
                                            Ditolak {{ $order->rejected_at?->format('d M Y, H:i') }}
                                        </div>
                                    @elseif($order->isExpired())
                                        <div class="tl-time" style="color:rgba(56,44,35,.4)">Kedaluwarsa</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Siap diambil --}}
                            <div class="tl-item">
                                <div class="tl-dot {{ $order->isReady() || $order->isCompleted() ? 'tl-done' : 'tl-miss' }}">
                                    {{ $order->isReady() || $order->isCompleted() ? '✓' : '○' }}
                                </div>
                                <div>
                                    <div class="tl-label" style="{{ !($order->isReady() || $order->isCompleted()) ? 'color:rgba(56,44,35,.35)' : '' }}">
                                        Siap Diambil
                                    </div>
                                </div>
                            </div>

                            {{-- Selesai --}}
                            <div class="tl-item">
                                <div class="tl-dot {{ $order->isCompleted() ? 'tl-done' : 'tl-miss' }}">
                                    {{ $order->isCompleted() ? '✓' : '○' }}
                                </div>
                                <div>
                                    <div class="tl-label" style="{{ !$order->isCompleted() ? 'color:rgba(56,44,35,.35)' : '' }}">
                                        Selesai (Pickup)
                                    </div>
                                    @if($order->completed_at)
                                        <div class="tl-time">{{ $order->completed_at->format('d M Y, H:i') }}</div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Info merchant --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Info Merchant</div></div>
                    <div class="panel-body">
                        <div class="info-row">
                            <div class="info-label">Nama Usaha</div>
                            <div class="info-val">{{ $order->merchant?->business_name ?? '—' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Alamat</div>
                            <div class="info-val">{{ $order->merchant?->business_address ?? '—' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pemilik</div>
                            <div class="info-val">{{ $order->merchant?->user?->name ?? '—' }}</div>
                        </div>
                        @if($order->merchant)
                        <div style="margin-top:.75rem">
                            <a href="{{ route('admin.merchants.show', $order->merchant) }}"
                               style="font-size:.78rem; color:var(--olive); font-weight:600; text-decoration:none;">
                                Lihat profil merchant →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>

</body>
</html>