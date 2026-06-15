{{-- resources/views/merchant/orders/index.blade.php --}}
@extends('layouts.merchant')

@section('title', 'Pesanan Masuk')
@section('active_nav', 'merchant.orders')

@section('styles')
<style>
    .pg-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; }
    .pg-title { font-family: var(--font-d); font-size: 1.5rem; }
    .pg-sub   { font-size: .8rem; color: var(--camel); margin-top: .2rem; }

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

    .orders-list { display: flex; flex-direction: column; gap: .85rem; }
    .order-card {
        background: white; border-radius: 14px;
        box-shadow: var(--shadow);
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

    .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .22rem .65rem; border-radius: 999px; font-size: .68rem; font-weight: 700; }
    .b-pending   { background: rgba(185,148,112,.12); color: #8a5c2a; }
    .b-confirmed { background: rgba(95,111,82,.12);   color: var(--olive); }
    .b-ready     { background: rgba(169,179,136,.2);  color: #3d5c2a; }
    .b-completed { background: rgba(95,111,82,.08);   color: var(--olive); }
    .b-rejected  { background: rgba(180,60,60,.08);   color: #a03030; }
    .b-expired   { background: rgba(56,44,35,.07);    color: rgba(56,44,35,.4); }

    .actions { display: flex; gap: .5rem; align-items: center; }
    .btn {
        padding: .3rem .8rem; border-radius: 8px; font-family: var(--font-b);
        font-size: .75rem; font-weight: 600; cursor: pointer; border: none;
        text-decoration: none; display: inline-flex; align-items: center;
        gap: .3rem; transition: all .15s;
    }
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

    .empty { text-align: center; padding: 4rem 1rem; background: white; border-radius: 14px; }
    .empty-icon { font-size: 2.5rem; opacity: .35; margin-bottom: .75rem; }
    .empty p { font-size: .85rem; color: var(--camel); }

    .pagination { display: flex; gap: .35rem; justify-content: center; margin-top: 1.25rem; }
    .pagination a, .pagination span {
        padding: .35rem .7rem; border-radius: 8px; font-size: .78rem; font-weight: 600;
        text-decoration: none; border: 1.5rem solid rgba(56,44,35,.1);
        border-width: 1.5px;
        color: rgba(56,44,35,.5); background: white;
    }
    .pagination .active-page { background: var(--charcoal); color: white; border-color: var(--charcoal); }

    .modal-overlay {
        position: fixed; inset: 0; background: rgba(56,44,35,.5); z-index: 200;
        display: none; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal { background: white; border-radius: 16px; padding: 1.5rem; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(56,44,35,.2); }
    .modal-title { font-family: var(--font-d); font-size: 1.1rem; margin-bottom: .35rem; }
    .modal-sub { font-size: .8rem; color: var(--camel); margin-bottom: 1rem; }
    .modal-textarea { width: 100%; padding: .7rem .9rem; border: 1.5px solid rgba(56,44,35,.15); border-radius: 10px; font-family: var(--font-b); font-size: .85rem; resize: vertical; min-height: 90px; outline: none; }
    .modal-textarea:focus { border-color: var(--laurel); }
    .modal-foot { display: flex; gap: .6rem; margin-top: 1rem; justify-content: flex-end; }
    .btn-cancel-modal { background: rgba(56,44,35,.07); color: var(--charcoal); padding: .45rem 1rem; border-radius: 8px; border: none; font-family: var(--font-b); font-size: .82rem; font-weight: 600; cursor: pointer; }
</style>
@endsection

@section('content')
<div class="pg-head">
    <div>
        <div class="pg-title">Pesanan Masuk</div>
        <div class="pg-sub">Kelola dan pantau seluruh pesanan dari pelanggan.</div>
    </div>
</div>

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
            <div class="order-head">
                <span class="order-code">{{ $order->pickup_code }}</span>
                <span class="order-user">{{ $order->user?->name ?? 'User' }}</span>
                <span class="order-time">{{ $order->ordered_at->diffForHumans() }}</span>
            </div>

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

            <div class="order-foot">
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

                <div class="actions">
                    <a href="{{ route('merchant.orders.show', $order) }}" class="btn btn-view">Detail</a>

                    @if($order->isPending())
                        <form method="POST" action="{{ route('merchant.orders.confirm', $order) }}">
                            @csrf
                            <button type="submit" class="btn btn-confirm">✓ Konfirmasi</button>
                        </form>
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
                        <a href="{{ route('merchant.orders.show', $order) }}" class="btn btn-complete">
                            ✔ Verifikasi Pickup
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

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
@endsection

@section('scripts')
<script>
function openRejectModal(orderId, pickupCode) {
    document.getElementById('rejectModalSub').textContent = 'Pesanan #' + pickupCode;
    document.getElementById('rejectForm').action = '/merchant/orders/' + orderId + '/reject';
    document.getElementById('rejectModal').classList.add('open');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('open');
}

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endsection