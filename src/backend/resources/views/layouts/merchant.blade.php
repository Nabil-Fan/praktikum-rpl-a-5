<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Merchant') — EcoEats</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --olive:    #5F6F52;
            --laurel:   #A9B388;
            --cornsilk: #FEFAE0;
            --camel:    #B99470;
            --charcoal: #382C23;
            --bg:       #f2ede4;
            --white:    #ffffff;
            --font-d:   'Fraunces', serif;
            --font-b:   'Plus Jakarta Sans', sans-serif;
            --shadow:   0 1px 3px rgba(56,44,35,.06), 0 4px 16px rgba(56,44,35,.07);
        }

        body {
            font-family: var(--font-b);
            background: var(--bg);
            color: var(--charcoal);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 230px; min-height: 100vh;
            background: var(--charcoal);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; z-index: 50;
        }
        .sb-brand {
            padding: 1.4rem 1.4rem 1rem;
            border-bottom: 1px solid rgba(254,250,224,.07);
        }
        .sb-brand-name {
            font-family: var(--font-d); font-size: 1.2rem; color: var(--cornsilk);
        }
        .sb-role {
            font-size: .65rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--camel); margin-top: .2rem;
        }
        .sb-user {
            padding: 1rem 1.4rem;
            border-bottom: 1px solid rgba(254,250,224,.07);
        }
        .sb-name { font-size: .83rem; font-weight: 600; color: var(--cornsilk); }
        .sb-sub  { font-size: .71rem; color: var(--camel); margin-top: .2rem; }

        nav.sb-nav { flex: 1; padding: .75rem 0; }
        .nav-label {
            font-size: .61rem; font-weight: 700; letter-spacing: .13em;
            text-transform: uppercase; color: rgba(254,250,224,.27);
            padding: .65rem 1.4rem .2rem;
        }
        .nav-item {
            display: flex; align-items: center; gap: .65rem;
            padding: .55rem 1.4rem; font-size: .8rem;
            color: rgba(254,250,224,.55); text-decoration: none;
            border-left: 2.5px solid transparent; transition: all .15s;
        }
        .nav-item:hover { color: var(--cornsilk); background: rgba(254,250,224,.04); }
        .nav-item.active {
            color: var(--cornsilk); border-left-color: var(--camel);
            background: rgba(185,148,112,.12); font-weight: 600;
        }
        .nav-badge {
            margin-left: auto; background: var(--camel); color: white;
            font-size: .6rem; font-weight: 700; padding: .1rem .45rem;
            border-radius: 999px; min-width: 18px; text-align: center;
        }

        .sb-footer {
            padding: 1rem 1.4rem;
            border-top: 1px solid rgba(254,250,224,.07);
        }
        .btn-logout {
            width: 100%; padding: .55rem; background: transparent;
            border: 1.5px solid rgba(254,250,224,.13); color: rgba(254,250,224,.45);
            border-radius: 8px; font-family: var(--font-b); font-size: .75rem;
            font-weight: 600; cursor: pointer; transition: all .2s;
        }
        .btn-logout:hover { border-color: rgba(254,250,224,.4); color: var(--cornsilk); }

        /* ── MAIN ── */
        .main { margin-left: 230px; flex: 1; padding: 2rem 2.25rem 4rem; min-width: 0; }

        /* ── FLASH ── */
        .flash {
            padding: .75rem 1rem; border-radius: 10px;
            font-size: .83rem; margin-bottom: 1.25rem;
        }
        .flash-success {
            background: rgba(95,111,82,.1); border: 1px solid rgba(95,111,82,.25);
            color: var(--olive);
        }
        .flash-error {
            background: rgba(180,60,60,.08); border: 1px solid rgba(180,60,60,.2);
            color: #a03030;
        }

        /* ── SHARED UTILITIES ── */
        .badge {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .22rem .65rem; border-radius: 999px;
            font-size: .68rem; font-weight: 700;
        }
        .b-pending   { background: rgba(185,148,112,.12); color: #8a5c2a; }
        .b-confirmed { background: rgba(95,111,82,.12);   color: var(--olive); }
        .b-ready     { background: rgba(169,179,136,.2);  color: #3d5c2a; }
        .b-completed { background: rgba(95,111,82,.08);   color: var(--olive); }
        .b-rejected  { background: rgba(180,60,60,.08);   color: #a03030; }
        .b-expired   { background: rgba(56,44,35,.07);    color: rgba(56,44,35,.4); }
        .b-available   { background: rgba(95,111,82,.12);  color: var(--olive); }
        .b-unavailable { background: rgba(56,44,35,.07);   color: rgba(56,44,35,.4); }
        .b-sold_out    { background: rgba(180,60,60,.08);  color: #a03030; }

        .btn {
            padding: .3rem .8rem; border-radius: 8px; font-family: var(--font-b);
            font-size: .75rem; font-weight: 600; cursor: pointer; border: none;
            text-decoration: none; display: inline-flex; align-items: center;
            gap: .3rem; transition: all .15s;
        }
        .btn-view    { background: rgba(56,44,35,.07); color: var(--charcoal); }
        .btn-view:hover { background: rgba(56,44,35,.12); }
        .btn-primary {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .6rem 1.2rem; background: var(--camel); color: white;
            border: none; border-radius: 10px; font-family: var(--font-b);
            font-weight: 700; font-size: .83rem; cursor: pointer;
            transition: background .2s; text-decoration: none;
            white-space: nowrap; flex-shrink: 0;
        }
        .btn-primary:hover { background: var(--charcoal); }
        .btn-primary.disabled {
            background: rgba(185,148,112,.4);
            cursor: not-allowed; pointer-events: none;
        }

        .pagination { display: flex; gap: .35rem; justify-content: center; margin-top: 1.25rem; }
        .pagination a, .pagination span {
            padding: .35rem .7rem; border-radius: 8px; font-size: .78rem; font-weight: 600;
            text-decoration: none; border: 1.5px solid rgba(56,44,35,.1);
            color: rgba(56,44,35,.5); background: white;
        }
        .pagination .active-page { background: var(--charcoal); color: white; border-color: var(--charcoal); }

        @media (max-width: 860px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
        }


        /* ── MOBILE HAMBURGER(Bar untuk mobile)── */
        .hamburger {
            display: none;
            position: fixed; top: 1rem; left: 1rem; z-index: 200;
            width: 40px; height: 40px;
            background: var(--charcoal); border: none; border-radius: 10px;
            cursor: pointer; align-items: center; justify-content: center;
            flex-direction: column; gap: 5px; padding: 10px;
        }
        .hamburger span {
            display: block; width: 20px; height: 2px;
            background: var(--cornsilk); border-radius: 2px;
            transition: all .2s;
        }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(56,44,35,.5); z-index: 49;
        }
        .sidebar-overlay.open { display: block; }

        @media (max-width: 860px) {
            .sidebar {
                display: flex;          /* tetap ada tapi tersembunyi di kiri */
                transform: translateX(-100%);
                transition: transform .25s ease;
            }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; padding-top: 4rem; }
            .hamburger { display: flex; }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
{{-- Mobile hamburger --}}
<button class="hamburger" id="hamburger" aria-label="Buka menu">
    <span></span><span></span><span></span>
</button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-brand-name">🌿 EcoEats</div>
        <div class="sb-role">Portal Merchant</div>
    </div>

    <div class="sb-user">
        <div class="sb-name">{{ auth()->user()->name }}</div>
        <div class="sb-sub">{{ $profile?->business_name ?? '—' }}</div>
    </div>

    <nav class="sb-nav">
        @php
            $activeNav = View::yieldContent('active_nav');
            $pendingOrders = isset($stats['pending_orders']) ? $stats['pending_orders'] : 0;
        @endphp

        <div class="nav-label">Utama</div>
        <a href="{{ route('merchant.dashboard') }}"
           class="nav-item {{ $activeNav === 'merchant.dashboard' ? 'active' : '' }}">
            📊 Dashboard
        </a>
        <a href="{{ route('merchant.listings.index') }}"
           class="nav-item {{ $activeNav === 'merchant.listings' ? 'active' : '' }}">
            🍱 Menu Surplus
        </a>
        <a href="{{ route('merchant.orders.index') }}"
           class="nav-item {{ $activeNav === 'merchant.orders' ? 'active' : '' }}">
            📋 Pesanan Masuk
            @if($pendingOrders > 0)
                <span class="nav-badge">{{ $pendingOrders }}</span>
            @endif
        </a>

        <div class="nav-label">Keuangan</div>
        <a href="{{ route('merchant.withdrawals.index') }}"
           class="nav-item {{ $activeNav === 'merchant.withdrawals' ? 'active' : '' }}">
            💰 Penarikan Dana
        </a>

        <div class="nav-label">Akun</div>
        <a href="{{ route('merchant.profile.edit') }}"
           class="nav-item {{ $activeNav === 'merchant.profile' ? 'active' : '' }}">
            🏪 Profil Usaha
        </a>
        <a href="{{ route('merchant.map') }}"
           class="nav-item {{ $activeNav === 'merchant.map' ? 'active' : '' }}">
            🗺 Lokasi Usaha
        </a>
    </nav>

    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="btn-logout">↩ Keluar</button>
        </form>
    </div>
</aside>

{{-- ── MAIN ── --}}
<main class="main">

    @if(session('success'))
        <div class="flash flash-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">✕ {{ session('error') }}</div>
    @endif

    @yield('content')

</main>

<script>
const hamburger = document.getElementById('hamburger');
const sidebar   = document.querySelector('.sidebar');
const overlay   = document.getElementById('sidebarOverlay');

function toggleSidebar() {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
}

function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
}

hamburger.addEventListener('click', toggleSidebar);
overlay.addEventListener('click', closeSidebar);

document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', closeSidebar);
});
</script>
@yield('scripts')
</body>
</html>