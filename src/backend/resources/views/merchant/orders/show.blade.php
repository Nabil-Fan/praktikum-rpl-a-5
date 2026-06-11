{{-- resources/views/merchant/orders/show.blade.php --}}
{{-- FIX: hapus duplikat <nav class="sb-nav">, tutup </nav> yang hilang --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $order->pickup_code }} — EcoEats</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:    #5F6F52; --laurel:  #A9B388;
            --cornsilk: #FEFAE0; --camel:   #B99470;
            --charcoal: #382C23; --bg:      #f3ede3;
            --font-d: 'Fraunces', serif; --font-b: 'Plus Jakarta Sans', sans-serif;
        }
        body { font-family: var(--font-b); background: var(--bg); color: var(--charcoal); min-height: 100vh; display: flex; }

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

        .main { margin-left: 230px; flex: 1; padding: 1.75rem 2rem 3rem; }
        .back-link { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; color: var(--camel); text-decoration: none; font-weight: 600; margin-bottom: 1.25rem; }
        .back-link:hover { color: var(--olive); }
        .pg-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .pg-title { font-family: var(--font-d); font-size: 1.45rem; }
        .pg-sub   { font-size: .8rem; color: var(--camel); margin-top: .2rem; }

        .flash { padding: .7rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .82rem; font-weight: 500; }
        .flash-success { background: rgba(95,111,82,.1);  color: var(--olive); border: 1px solid rgba(95,111,82,.2); }
        .flash-error   { background: rgba(180,60,60,.07); color: #a03030;      border: 1px solid rgba(180,60,60,.15); }

        .grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.25rem; }
        .panel { background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(56,44,35,.07); overflow: hidden; margin-bottom: 1.25rem; }
        .panel-hd { padding: 1rem 1.25rem; border-bottom: 1px solid rgba(56,44,35,.06); display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-family: var(--font-d); font-size: 1rem; }
        .panel-body { padding: 1.1rem 1.25rem; }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: .5rem .75rem; font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: rgba(56,44,35,.35); border-bottom: 1px solid rgba(56,44,35,.07); }
        td { padding: .7rem .75rem; font-size: .82rem; border-bottom: 1px solid rgba(56,44,35,.05); }
        tr:last-child td { border-bottom: none; }
        .td-name  { font-weight: 600; }
        .td-qty   { color: var(--camel); text-align: center; }
        .td-price { color: var(--camel); }
        .td-sub   { font-weight: 700; color: var(--olive); text-align: right; }
        .tfoot-row td { border-top: 1.5px solid rgba(56,44,35,.1); font-weight: 700; padding-top: .85rem; }
        .tfoot-label { font-size: .8rem; color: var(--camel); }
        .tfoot-total { font-family: var(--font-d); font-size: 1.1rem; color: var(--charcoal); text-align: right; }

        .info-list { display: flex; flex-direction: column; gap: .7rem; }
        .info-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; padding-bottom: .65rem; border-bottom: 1px solid rgba(56,44,35,.05); }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-lbl { font-size: .75rem; color: var(--camel); flex-shrink: 0; }
        .info-val { font-size: .82rem; font-weight: 600; text-align: right; }

        .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .22rem .65rem; border-radius: 999px; font-size: .68rem; font-weight: 700; }
        .b-pending   { background: rgba(185,148,112,.12); color: #8a5c2a; }
        .b-confirmed { background: rgba(95,111,82,.12);   color: var(--olive); }
        .b-ready     { background: rgba(169,179,136,.2);  color: #3d5c2a; }
        .b-completed { background: rgba(95,111,82,.08);   color: var(--olive); }
        .b-rejected  { background: rgba(180,60,60,.08);   color: #a03030; }
        .b-expired   { background: rgba(56,44,35,.07);    color: rgba(56,44,35,.4); }

        .pickup-box { background: var(--charcoal); border-radius: 12px; padding: 1.25rem; text-align: center; margin-bottom: 1.25rem; }
        .pickup-label { font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--camel); margin-bottom: .5rem; }
        .pickup-code  { font-family: var(--font-d); font-size: 2.2rem; color: var(--cornsilk); letter-spacing: .2em; }

        .action-panel { background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(56,44,35,.07); padding: 1.25rem; }
        .action-title { font-family: var(--font-d); font-size: .95rem; margin-bottom: .9rem; }
        .btn { width: 100%; padding: .7rem; border-radius: 10px; font-family: var(--font-b); font-size: .85rem; font-weight: 700; cursor: pointer; border: none; transition: all .15s; margin-bottom: .6rem; }
        .btn:last-child { margin-bottom: 0; }
        .btn-confirm  { background: var(--olive);   color: white; }
        .btn-confirm:hover  { background: #4a5640; }
        .btn-reject   { background: transparent; color: #a03030; border: 1.5px solid rgba(180,60,60,.25); }
        .btn-reject:hover   { background: rgba(180,60,60,.06); }
        .btn-ready    { background: var(--laurel);  color: var(--charcoal); }
        .btn-ready:hover    { background: #8fa070; color: white; }
        .btn-complete-inline { background: var(--camel); color: white; }
        .btn-complete-inline:hover { background: var(--charcoal); }
        .btn-disabled { background: rgba(56,44,35,.07); color: rgba(56,44,35,.3); cursor: not-allowed; }

        .verify-form { margin-top: .9rem; padding-top: .9rem; border-top: 1px solid rgba(56,44,35,.08); }
        .verify-label { font-size: .75rem; color: var(--camel); margin-bottom: .45rem; display: block; font-weight: 600; }
        .verify-input { width: 100%; padding: .65rem .9rem; border: 1.5px solid rgba(56,44,35,.15); border-radius: 9px; font-family: var(--font-d); font-size: 1.1rem; letter-spacing: .15em; text-align: center; text-transform: uppercase; outline: none; }
        .verify-input:focus { border-color: var(--camel); }
        .verify-hint { font-size: .72rem; color: var(--camel); margin-top: .4rem; text-align: center; }

        .timeline { display: flex; flex-direction: column; gap: .6rem; }
        .tl-item { display: flex; gap: .75rem; align-items: flex-start; }
        .tl-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; margin-top: .25rem; }
        .tl-dot.done  { background: var(--olive); }
        .tl-dot.empty { background: rgba(56,44,35,.15); }
        .tl-label { font-size: .78rem; font-weight: 600; }
        .tl-time  { font-size: .7rem;  color: var(--camel); }

        .reject-textarea { width: 100%; padding: .65rem .9rem; border: 1.5px solid rgba(180,60,60,.25); border-radius: 9px; font-family: var(--font-b); font-size: .84rem; resize: vertical; min-height: 80px; outline: none; margin-top: .75rem; }
        .reject-textarea:focus { border-color: #a03030; }

        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } .sidebar { display: none; } .main { margin-left: 0; } }
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
        <a href="{{ route('merchant.orders.index') }}" class="nav-item active">📋 Pesanan Masuk</a>
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

<main class="main">

    <a href="{{ route('merchant.orders.index') }}" class="back-link">← Kembali ke Daftar Pesanan</a>

    <div class="pg-head">
        <div>
            <div class="pg-title">Pesanan #{{ $order->pickup_code }}</div>
            <div class="pg-sub">
                Dipesan {{ $order->ordered_at->format('d M Y, H:i') }}
                oleh {{ $order->user?->name ?? 'User' }}
            </div>
        </div>
        @php
            $badgeClass = match($order->status) {
                'pending'   => 'b-pending',
                'confirmed' => 'b-confirmed',
                'ready'     => 'b-ready',
                'completed' => 'b-completed',
                'rejected'  => 'b-rejected',
                default     => 'b-expired',
            };
        @endphp
        <span class="badge {{ $badgeClass }}" style="font-size:.8rem; padding:.35rem 1rem;">
            {{ $order->statusLabel() }}
        </span>
    </div>

    @if(session('success'))
        <div class="flash flash-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">✕ {{ session('error') }}</div>
    @endif

    <div class="grid">

        <div>
            <div class="panel">
                <div class="panel-hd">
                    <div class="panel-title">Item Pesanan</div>
                    <span style="font-size:.75rem;color:var(--camel);">{{ $order->items->count() }} item</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align:center">Qty</th>
                            <th>Harga Satuan</th>
                            <th style="text-align:right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="td-name">{{ $item->listing_name }}</td>
                            <td class="td-qty">{{ $item->quantity }}</td>
                            <td class="td-price">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="td-sub">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="tfoot-row">
                            <td colspan="3" class="tfoot-label">Total Pembayaran</td>
                            <td class="tfoot-total">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="panel">
                <div class="panel-hd"><div class="panel-title">Info Pesanan</div></div>
                <div class="panel-body">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-lbl">Metode Pembayaran</span>
                            <span class="info-val" style="text-transform:uppercase;">{{ $order->payment_method }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-lbl">Batas Konfirmasi</span>
                            <span class="info-val">{{ $order->expires_at?->format('d M Y, H:i') ?? '—' }}</span>
                        </div>
                        @if($order->confirmed_at)
                        <div class="info-row">
                            <span class="info-lbl">Dikonfirmasi pada</span>
                            <span class="info-val">{{ $order->confirmed_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                        @if($order->completed_at)
                        <div class="info-row">
                            <span class="info-lbl">Selesai pada</span>
                            <span class="info-val">{{ $order->completed_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                        @if($order->rejected_at)
                        <div class="info-row">
                            <span class="info-lbl">Ditolak pada</span>
                            <span class="info-val" style="color:#a03030">{{ $order->rejected_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-hd"><div class="panel-title">Riwayat Status</div></div>
                <div class="panel-body">
                    <div class="timeline">
                        @php
                            $steps = [
                                ['label' => 'Pesanan Dibuat',    'time' => $order->ordered_at],
                                ['label' => 'Dikonfirmasi',      'time' => $order->confirmed_at],
                                ['label' => 'Siap Diambil',      'time' => null],
                                ['label' => 'Selesai / Pickup',  'time' => $order->completed_at],
                            ];
                        @endphp
                        @foreach($steps as $step)
                        <div class="tl-item">
                            <div class="tl-dot {{ $step['time'] ? 'done' : 'empty' }}"></div>
                            <div>
                                <div class="tl-label" style="{{ !$step['time'] ? 'color:rgba(56,44,35,.3)' : '' }}">
                                    {{ $step['label'] }}
                                </div>
                                @if($step['time'])
                                    <div class="tl-time">{{ $step['time']->format('d M Y, H:i') }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @if($order->isRejected())
                        <div class="tl-item">
                            <div class="tl-dot" style="background:#a03030"></div>
                            <div>
                                <div class="tl-label" style="color:#a03030">Ditolak</div>
                                <div class="tl-time">{{ $order->rejected_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div>
            @if(!$order->isRejected() && !$order->isExpired())
            <div class="pickup-box">
                <div class="pickup-label">Kode Pickup</div>
                <div class="pickup-code">{{ $order->pickup_code }}</div>
            </div>
            @endif

            <div class="action-panel">
                <div class="action-title">Tindakan</div>

                @if($order->isPending())
                    <form method="POST" action="{{ route('merchant.orders.confirm', $order) }}">
                        @csrf
                        <button type="submit" class="btn btn-confirm">✓ Konfirmasi Pesanan</button>
                    </form>
                    <form method="POST" action="{{ route('merchant.orders.reject', $order) }}" id="rejectFormDetail">
                        @csrf
                        <button type="button" class="btn btn-reject" onclick="toggleRejectForm()">✕ Tolak Pesanan</button>
                        <div id="rejectFields" style="display:none">
                            <textarea class="reject-textarea" name="rejection_reason"
                                      placeholder="Alasan penolakan (wajib)…" required></textarea>
                            <button type="submit" class="btn btn-reject" style="margin-top:.6rem">Konfirmasi Penolakan</button>
                        </div>
                    </form>

                @elseif($order->canMarkReady())
                    <form method="POST" action="{{ route('merchant.orders.ready', $order) }}">
                        @csrf
                        <button type="submit" class="btn btn-ready">🔔 Tandai Siap Diambil</button>
                    </form>

                @elseif($order->canComplete())
                    <p style="font-size:.78rem;color:var(--camel);margin-bottom:.75rem;line-height:1.5;">
                        Minta user menunjukkan kode pickup mereka, lalu masukkan di bawah untuk menyelesaikan pesanan.
                    </p>
                    <form method="POST" action="{{ route('merchant.orders.complete', $order) }}">
                        @csrf
                        <div class="verify-form">
                            <label class="verify-label">Masukkan Kode Pickup User</label>
                            <input class="verify-input" type="text" name="pickup_code"
                                   maxlength="10" placeholder="XXXXXXXX"
                                   autocomplete="off" autofocus>
                            <div class="verify-hint">Kode tidak case-sensitive</div>
                        </div>
                        <button type="submit" class="btn btn-complete-inline" style="margin-top:.85rem">
                            ✔ Selesaikan Pesanan
                        </button>
                    </form>

                @else
                    <button class="btn btn-disabled" disabled>
                        {{ $order->statusLabel() }}
                    </button>
                @endif
            </div>
        </div>

    </div>
</main>

<script>
function toggleRejectForm() {
    const el = document.getElementById('rejectFields');
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
</body>
</html>