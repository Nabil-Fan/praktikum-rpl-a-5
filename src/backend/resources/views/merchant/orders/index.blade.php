{{-- resources/views/merchant/orders/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk — EcoEats Merchant</title>
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

        /* ── SIDEBAR ── */
        .sidebar {
            width: 230px; min-height: 100vh; background: var(--charcoal);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; z-index: 50;
        }
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

        /* ── MAIN ── */
        .main { margin-left: 230px; flex: 1; padding: 1.75rem 2rem 3rem; }

        /* Page header */
        .pg-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; }
        .pg-title { font-family: var(--font-d); font-size: 1.5rem; }
        .pg-sub   { font-size: .8rem; color: var(--camel); margin-top: .2rem; }

        /* Flash */
        .flash { padding: .7rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .82rem; font-weight: 500; }
        .flash-success { background: rgba(95,111,82,.1);  color: var(--olive); border: 1px solid rgba(95,111,82,.2); }
        .flash-error   { background: rgba(180,60,60,.07); color: #a03030;      border: 1px solid rgba(180,60,60,.15); }

        /* Status tabs */
        .tab-bar { display: flex; gap: .35rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
        .tab-link {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .35rem .85rem; border-radius: 999px; font-size: .75rem; font-weight: 600;
            text-decoration: none; border: 1.5px solid rgba(56,44,35,.12);
            color: rgba(56,44,35,.5); background: white; transition: all .15s;
        }
        .tab-link:hover { border-color: var(--laurel); color: var(--olive); }
        .tab-link.active { background: var(--charcoal); color: var(--cornsilk); border-color: var(--charcoal); }
        .tab-count { font-size: .65rem; background: rgba(255,255,255,.25); padding: .05rem .35rem; border-radius: 999px; }
        .tab-link:not(.active) .tab-count { background: rgba(56,44,35,.08); color: rgba(56,44,35,.5); }

        /* Order cards */
        .orders-list { display: flex; flex-direction: column; gap: .85rem; }
        .order-card {
            background: white; border-radius: 14px;
            box-shadow: 0 1px 3px rgba(56,44,35,.07);
            overflow: hidden; transition: box-shadow .15s;
        }
        .order-card:hover { box-shadow: 0 4px 16px rgba(56,44,35,.1); }

        .order-head {
            display: flex; align-items: center; gap: 1rem;
            padding: .9rem 1.25rem; border-bottom: 1px solid rgba(56,44,35,.05);
        }
        .order-code {
            font-family: var(--font-d); font-size: 1rem; color: var(--charcoal);
            background: rgba(56,44,35,.05); padding: .2rem .65rem; border-radius: 8px;
            letter-spacing: .05em;
        }
        .order-user { font-size: .8rem; color: var(--camel); }
        .order-time { font-size: .75rem; color: rgba(56,44,35,.35); margin-left: auto; }

        .order-body { display: flex; align-items: center; gap: 1rem; padding: .85rem 1.25rem; }
        .order-items-preview { flex: 1; }
        .order-item-row { font-size: .82rem; color: var(--charcoal); display: flex; gap: .5rem; }
        .order-item-row + .order-item-row { margin-top: .25rem; }
        .order-item-qty { color: var(--camel); font-weight: 600; min-width: 24px; }
        .order-more { font-size: .72rem; color: var(--camel); margin-top: .3rem; }

        .order-total { text-align: right; flex-shrink: 0; }
        .order-total-label { font-size: .68rem; color: var(--camel); text-transform: uppercase; letter-spacing: .05em; }
        .order-total-amount { font-family: var(--font-d); font-size: 1.15rem; color: var(--olive); }

        .order-foot {
            display: flex; align-items: center; justify-content: space-between;
            padding: .7rem 1.25rem; border-top: 1px solid rgba(56,44,35,.05);
            background: rgba(56,44,35,.015);
        }

        /* Badge status */
        .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .22rem .65rem; border-radius: 999px; font-size: .68rem; font-weight: 700; }
        .b-pending   { background: rgba(185,148,112,.12); color: #8a5c2a; }
        .b-confirmed { background: rgba(95,111,82,.12);   color: var(--olive); }
        .b-ready     { background: rgba(169,179,136,.2);  color: #3d5c2a; }
        .b-completed { background: rgba(95,111,82,.08);   color: var(--olive); }
        .b-rejected  { background: rgba(180,60,60,.08);   color: #a03030; }
        .b-expired   { background: rgba(56,44,35,.07);    color: rgba(56,44,35,.4); }

        /* Action buttons */
        .actions { display: flex; gap: .5rem; align-items: center; }
        .btn { padding: .3rem .8rem; border-radius: 8px; font-family: var(--font-b); font-size: .75rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: .3rem; transition: all .15s; }
        .btn-view     { background: rgba(56,44,35,.07); color: var(--charcoal); }
        .btn-view:hover { background: rgba(56,44,35,.12); }
        .btn-confirm  { background: var(--olive); color: white; }
        .btn-confirm:hover { background: #4a5640; }
        .btn-reject   { background: transparent; color: #a03030; border: 1.5px solid rgba(180,60,60,.25); }
        .btn-reject:hover { background: rgba(180,60,60,.06); }
        .btn-ready    { background: var(--laurel); color: var(--charcoal); }
        .btn-ready:hover { background: #8fa070; color: white; }
        .btn-complete { background: var(--camel); color: white; }
        .btn-complete:hover { background: var(--charcoal); }

        /* Payment badge */
        .pay-badge { font-size: .7rem; color: var(--camel); display: flex; align-items: center; gap: .3rem; }

        /* Empty state */
        .empty { text-align: center; padding: 4rem 1rem; background: white; border-radius: 14px; }
        .empty-icon { font-size: 2.5rem; opacity: .35; margin-bottom: .75rem; }
        .empty p { font-size: .85rem; color: var(--camel); }

        /* Pagination */
        .pagination { display: flex; gap: .35rem; justify-content: center; margin-top: 1.25rem; }
        .pagination a, .pagination span {
            padding: .35rem .7rem; border-radius: 8px; font-size: .78rem; font-weight: 600;
            text-decoration: none; border: 1.5px solid rgba(56,44,35,.1);
            color: rgba(56,44,35,.5); background: white;
        }
        .pagination .active-page { background: var(--charcoal); color: white; border-color: var(--charcoal); }

        /* Reject modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(56,44,35,.5); z-index: 200; display: none; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: white; border-radius: 16px; padding: 1.5rem; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(56,44,35,.2); }
        .modal-title { font-family: var(--font-d); font-size: 1.1rem; margin-bottom: .35rem; }
        .modal-sub { font-size: .8rem; color: var(--camel); margin-bottom: 1rem; }
        .modal-textarea { width: 100%; padding: .7rem .9rem; border: 1.5px solid rgba(56,44,35,.15); border-radius: 10px; font-family: var(--font-b); font-size: .85rem; resize: vertical; min-height: 90px; outline: none; }
        .modal-textarea:focus { border-color: var(--laurel); }
        .modal-foot { display: flex; gap: .6rem; margin-top: 1rem; justify-content: flex-end; }
        .btn-cancel-modal { background: rgba(56,44,35,.07); color: var(--charcoal); padding: .45rem 1rem; border-radius: 8px; border: none; font-family: var(--font-b); font-size: .82rem; font-weight: 600; cursor: pointer; }
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
        <a href="{{ route('merchant.dashboard') }}" class="nav-item">📊 Dashboard</a>
        <a href="{{ route('merchant.listings.index') }}" class="nav-item">🍱 Menu Surplus</a>
        <a href="{{ route('merchant.orders.index') }}" class="nav-item active">📋 Pesanan Masuk</a>
        <div class="nav-label">Keuangan</div>
        <a href="{{ route('merchant.withdrawals.index') }}" class="nav-item">💰 Penarikan Dana</a>
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

    <div class="pg-head">
        <div>
            <div class="pg-title">Pesanan Masuk</div>
            <div class="pg-sub">Kelola dan pantau seluruh pesanan dari pelanggan.</div>
        </div>
    </div>

    @if(session('success'))
        <div class="flash flash-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">✕ {{ session('error') }}</div>
    @endif

    {{-- STATUS TABS --}}
    <div class="tab-bar">
        @php
            $tabs = [
                'all'       => 'Semua',
                'pending'   => 'Menunggu',
                'confirmed' => 'Dikonfirmasi',
                'ready'     => 'Siap Diambil',
                'completed' => 'Selesai',
                'rejected'  => 'Ditolak',
                'expired'   => 'Kedaluwarsa',
            ];
        @endphp
        @foreach($tabs as $key => $label)
            <a href="{{ route('merchant.orders.index', ['status' => $key]) }}"
               class="tab-link {{ $status === $key ? 'active' : '' }}">
                {{ $label }}
                <span class="tab-count">{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </div>

    {{-- ORDER LIST --}}
    @if($orders->isEmpty())
        <div class="empty">
            <div class="empty-icon">📋</div>
            <p>Belum ada pesanan
                {{ $status !== 'all' ? 'dengan status "' . $tabs[$status] . '"' : '' }}.
            </p>
        </div>
    @else
        <div class="orders-list">
            @foreach($orders as $order)
            <div class="order-card">
                {{-- HEAD --}}
                <div class="order-head">
                    <span class="order-code">{{ $order->pickup_code }}</span>
                    <span class="order-user">{{ $order->user?->name ?? 'User' }}</span>
                    <span class="order-time">{{ $order->ordered_at->diffForHumans() }}</span>
                </div>

                {{-- BODY --}}
                <div class="order-body">
                    <div class="order-items-preview">
                        @foreach($order->items->take(2) as $item)
                            <div class="order-item-row">
                                <span class="order-item-qty">{{ $item->quantity }}×</span>
                                <span>{{ $item->listing_name }}</span>
                            </div>
                        @endforeach
                        @if($order->items->count() > 2)
                            <div class="order-more">+{{ $order->items->count() - 2 }} item lainnya</div>
                        @endif
                    </div>
                    <div class="order-total">
                        <div class="order-total-label">Total</div>
                        <div class="order-total-amount">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="order-foot">
                    {{-- Badge status --}}
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
                    <span class="badge {{ $badgeClass }}">{{ $order->statusLabel() }}</span>

                    {{-- Action buttons --}}
                    <div class="actions">
                        <a href="{{ route('merchant.orders.show', $order) }}" class="btn btn-view">Detail</a>

                        @if($order->isPending())
                            {{-- Konfirmasi --}}
                            <form method="POST" action="{{ route('merchant.orders.confirm', $order) }}">
                                @csrf
                                <button type="submit" class="btn btn-confirm">✓ Konfirmasi</button>
                            </form>
                            {{-- Tolak → buka modal --}}
                            <button class="btn btn-reject"
                                    onclick="openRejectModal('{{ $order->id }}', '{{ $order->pickup_code }}')">
                                ✕ Tolak
                            </button>

                        @elseif($order->canMarkReady())
                            <form method="POST" action="{{ route('merchant.orders.ready', $order) }}">
                                @csrf
                                <button type="submit" class="btn btn-ready">🔔 Siap Diambil</button>
                            </form>

                        @elseif($order->canComplete())
                            {{-- Selesai hanya dari halaman detail karena perlu input kode --}}
                            <a href="{{ route('merchant.orders.show', $order) }}" class="btn btn-complete">
                                ✔ Verifikasi Pickup
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        @if($orders->hasPages())
            <div class="pagination">
                @if($orders->onFirstPage())
                    <span>‹</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}">‹</a>
                @endif

                @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if($page == $orders->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}">›</a>
                @else
                    <span>›</span>
                @endif
            </div>
        @endif
    @endif

</main>

{{-- REJECT MODAL --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <div class="modal-title">Tolak Pesanan</div>
        <div class="modal-sub" id="rejectModalSub">Pesanan #—</div>
        <form method="POST" id="rejectForm">
            @csrf
            <textarea class="modal-textarea" name="rejection_reason"
                      placeholder="Tulis alasan penolakan (wajib diisi)…" required></textarea>
            <div class="modal-foot">
                <button type="button" class="btn-cancel-modal" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn btn-reject" style="border-width:1.5px">Tolak Pesanan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(orderId, pickupCode) {
    document.getElementById('rejectModalSub').textContent = 'Pesanan #' + pickupCode;
    document.getElementById('rejectForm').action = '/merchant/orders/' + orderId + '/reject';
    document.getElementById('rejectModal').classList.add('open');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('open');
}
// Tutup modal jika klik overlay
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
</body>
</html>