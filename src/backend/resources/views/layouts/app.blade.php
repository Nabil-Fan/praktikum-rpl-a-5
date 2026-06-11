<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoEats — @yield('title', 'Dashboard')</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    /* ── base.css ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #FEFAE0; --card: #ffffff; --lbl: #503321; --sub: #8A7560;
      --inp: #F5F0CE; --brd: #DDD8B0; --btn: #B99470; --bth: #9A7A58;
      --grn: #5F6F52; --grnh: #4A5840; --blu: #6A7F8A; --red: #B84040;
      --org: #B87820; --inf: #F8F5DC; --ibd: #E4DDB0;
      --shadow: 0 2px 4px rgba(80,51,33,.04), 0 8px 24px rgba(80,51,33,.09);
    }
    body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--lbl); font-size: 14px; line-height: 1.5; }
    h1,h2,h3,h4,.playfair { font-family: 'Playfair Display', serif; }
    .hide { display: none !important; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-thumb { background: var(--brd); border-radius: 3px; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .fade-in { animation: fadeUp .3s ease both; }

    /* ── layout.css ── */
    #page-app { display:flex; height:100vh; overflow:hidden; }
    #sidebar { width:228px; flex-shrink:0; background:#5F6F52; display:flex; flex-direction:column; }
    .sb-header { padding:20px 16px 16px; border-bottom:1px solid rgba(255,255,255,.08); display:flex; align-items:center; gap:10px; }
    .sb-logo-icon { display:none; }
    .sb-header-text .sb-name { font-family:'Playfair Display',serif; font-size:18px; color:#fff; line-height:1.2; }
    .sb-header-text #sb-role-label { font-size:10px; color:rgba(255,255,255,.45); margin-top:3px; letter-spacing:.06em; text-transform:uppercase; }
    .sb-nav { flex:1; overflow-y:auto; padding:10px 8px; }
    .sb-section { font-size:9.5px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.30); padding:14px 10px 5px; }
    .sb-item { display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:8px; cursor:pointer; width:100%; background:transparent; border:none; font-family:'DM Sans',sans-serif; font-size:13.5px; font-weight:500; color:rgba(255,255,255,.55); transition:all .15s; text-align:left; position:relative; }
    .sb-item:hover { background:rgba(255,255,255,.07); color:rgba(255,255,255,.90); }
    .sb-item.active { background:rgba(255,255,255,.12); color:#fff; font-weight:600; }
    .sb-item.active::before { content:''; position:absolute; left:0; top:20%; height:60%; width:3px; background:#FEFAE0; border-radius:0 2px 2px 0; }
    .sb-item-icon { font-size:15px; width:20px; text-align:center; flex-shrink:0; }
    .sb-badge { margin-left:auto; background:rgba(255,255,255,.15); color:#fff; font-size:9px; font-weight:700; padding:2px 7px; border-radius:10px; }
    .sb-badge.grn { background:#B99470; }
    .sb-footer { padding:12px 8px; border-top:1px solid rgba(255,255,255,.08); }
    #main-area { flex:1; display:flex; flex-direction:column; overflow:hidden; }
    .topbar { background:white; border-bottom:1px solid var(--brd); padding:14px 24px; display:flex; align-items:center; gap:12px; }
    .topbar-titles { flex:1; }
    #topbar-title { font-family:'Playfair Display',serif; font-size:20px; color:var(--lbl); line-height:1.1; }
    #topbar-sub { font-size:12px; color:var(--sub); margin-top:2px; }
    #topbar-action { padding:9px 16px; border-radius:8px; border:none; background:var(--grn); color:white; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:600; cursor:pointer; transition:all .2s; }
    #topbar-action:hover { opacity:.88; }
    .topbar-avatar { width:34px; height:34px; border-radius:50%; background:var(--inp); border:1.5px solid var(--brd); display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
    .main-scroll { flex:1; overflow-y:auto; padding:24px; }

    /* ── components.css ── */
    .btn { padding:9px 18px; border-radius:8px; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:600; transition:all .2s; display:inline-flex; align-items:center; gap:6px; }
    .btn:hover { opacity:.85; } .btn:active { transform:scale(.97); }
    .btn-grn { background:var(--grn); color:white; } .btn-brn { background:var(--btn); color:white; }
    .btn-out { background:transparent; border:1.5px solid var(--brd); color:var(--lbl); }
    .btn-red { background:#FDE8E8; color:var(--red); border:1px solid #F5C6C2; }
    .btn-sm  { padding:5px 12px; font-size:12px; border-radius:6px; }
    .card2 { background:white; border:1px solid var(--brd); border-radius:12px; overflow:hidden; }
    .card-head { padding:14px 18px; border-bottom:1px solid var(--brd); display:flex; align-items:center; justify-content:space-between; font-size:14px; font-weight:600; color:var(--lbl); }
    .card-body { padding:18px; }
    .stat-card { background:white; border:1px solid var(--brd); border-radius:12px; padding:16px 18px; border-top:3px solid var(--brd); }
    .st-label { font-size:11.5px; color:var(--sub); font-weight:500; margin-bottom:8px; }
    .st-val   { font-size:26px; font-weight:700; color:var(--lbl); line-height:1; }
    .st-delta { font-size:11px; font-weight:500; margin-top:5px; }
    .d-grn { color:var(--grn); } .d-red { color:var(--red); } .d-org { color:var(--org); }
    .badge { display:inline-flex; padding:3px 8px; border-radius:4px; font-size:11px; font-weight:600; }
    .badge-grn { background:#E4ECD8; color:#3A4D2A; } .badge-org { background:#F8EDD8; color:#7A4E00; }
    .badge-red { background:#F8E0E0; color:#742020; } .badge-gray { background:var(--inp); color:var(--sub); }
    .tbl { width:100%; border-collapse:collapse; font-size:13px; }
    .tbl th { text-align:left; padding:9px 12px; font-size:10.5px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:var(--sub); border-bottom:1px solid var(--brd); background:#F8F4D8; }
    .tbl td { padding:11px 12px; border-bottom:1px solid #E8E2C0; color:var(--lbl); }
    .tbl tr:last-child td { border-bottom:none; } .tbl tr:hover td { background:#F8F4D8; }
    .form-group { margin-bottom:14px; }
    .form-label { display:block; font-size:12px; font-weight:600; color:var(--sub); text-transform:uppercase; letter-spacing:.06em; margin-bottom:5px; }
    .form-input,.form-select,.form-textarea { width:100%; padding:10px 13px; background:var(--inp); border:1.5px solid transparent; border-radius:8px; font-family:'DM Sans',sans-serif; font-size:13.5px; color:var(--lbl); outline:none; transition:all .2s; }
    .form-input:focus,.form-select:focus,.form-textarea:focus { border-color:var(--btn); box-shadow:0 0 0 3px rgba(185,148,112,.16); }
    .alert { padding:11px 15px; border-radius:8px; font-size:13px; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
    .alert-org { background:#F8EDD8; border:1px solid #E8C880; color:#6A3800; }
    .alert-grn { background:#E4ECD8; border:1px solid #A9B388; color:#2E4020; }
    .alert-red { background:#F8E0E0; border:1px solid #E8A8A8; color:#6A1818; }
    .inner-tabs { display:flex; border-bottom:1px solid var(--brd); margin-bottom:16px; }
    .inner-tab { padding:10px 18px; font-size:13px; font-weight:500; cursor:pointer; color:var(--sub); border-bottom:2px solid transparent; margin-bottom:-1px; transition:all .15s; background:none; border-top:none; border-left:none; border-right:none; font-family:'DM Sans',sans-serif; }
    .inner-tab:hover { color:var(--lbl); } .inner-tab.active { color:var(--btn); border-bottom-color:var(--btn); font-weight:600; }
    .grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
    .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .flex-row { display:flex; align-items:center; gap:10px; }
    .flex-between { display:flex; align-items:center; justify-content:space-between; }
    .mb-8{margin-bottom:8px;} .mb-12{margin-bottom:12px;} .mb-16{margin-bottom:16px;} .mb-20{margin-bottom:20px;} .mt-16{margin-top:16px;}
    .text-sm{font-size:12px;} .text-xs{font-size:11px;} .color-sub{color:var(--sub);} .color-grn{color:var(--grn);} .color-red{color:var(--red);}
    .saldo-card { background:linear-gradient(135deg,#5F6F52,#4A5840); border-radius:14px; padding:20px 22px; color:white; margin-bottom:20px; }
    .saldo-lbl { font-size:11px; text-transform:uppercase; letter-spacing:.1em; opacity:.7; margin-bottom:6px; }
    .saldo-val { font-family:'Playfair Display',serif; font-size:32px; font-weight:700; line-height:1; }
    .listing-card { background:white; border:1px solid var(--brd); border-radius:10px; overflow:hidden; }
    .listing-img { height:110px; display:flex; align-items:center; justify-content:center; font-size:42px; }
    .listing-body { padding:12px 14px; } .listing-footer { padding:9px 14px; border-top:1px solid var(--brd); display:flex; gap:7px; }
    .verif-card { background:white; border:1px solid var(--brd); border-radius:12px; padding:18px; margin-bottom:12px; }
    .verif-card.done { opacity:.5; pointer-events:none; }
    .search-wrap { position:relative; flex:1; }
    .search-wrap input { width:100%; padding:9px 12px 9px 34px; background:var(--inp); border:1.5px solid transparent; border-radius:8px; font-family:'DM Sans',sans-serif; font-size:13px; color:var(--lbl); outline:none; }
    .search-wrap input:focus { border-color:var(--btn); }
    .prog-wrap { height:5px; background:var(--inp); border-radius:3px; overflow:hidden; margin:4px 0 10px; }
    .prog-fill { height:100%; border-radius:3px; background:var(--grn); }
    .chart-wrap { display:flex; align-items:flex-end; gap:6px; height:80px; border-bottom:1.5px solid var(--brd); }
    .chart-bar { flex:1; border-radius:3px 3px 0 0; transition:opacity .2s; cursor:pointer; }
    .chart-bar.hi { background:var(--grn); } .chart-bar.lo { background:#A9B388; }
    .chart-labels { display:flex; gap:6px; margin-top:4px; }
    .chart-labels span { flex:1; text-align:center; font-size:10px; color:var(--sub); }
  </style>
  @yield('extra-css')
</head>
<body>
<div id="page-app">

  <!-- Sidebar -->
  <aside id="sidebar">
    <div class="sb-header">
      <div class="sb-logo-icon"></div>
      <div class="sb-header-text">
        <div class="sb-name">EcoEats</div>
        <div id="sb-role-label">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Mitra Merchant' }}</div>
      </div>
    </div>
    <nav class="sb-nav" id="sb-nav"></nav>
    <div class="sb-footer">
      {{-- Logout via Laravel POST --}}
      <form method="POST" action="{{ route('logout') }}" id="logout-form">
        @csrf
        <button type="submit" class="sb-item">
          <span class="sb-item-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </span>
          Keluar
        </button>
      </form>
    </div>
  </aside>

  <!-- Main area -->
  <div id="main-area">
    <div class="topbar">
      <div class="topbar-titles">
        <div id="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div id="topbar-sub">@yield('page-sub', '')</div>
      </div>
      <button id="topbar-action" class="hide" onclick="topbarAction()"></button>
      <div class="topbar-avatar">{{ Auth::user()->role === 'admin' ? 'A' : 'M' }}</div>
    </div>
    <div class="main-scroll fade-in" id="main-scroll">
      @yield('content')
    </div>
  </div>

</div>

{{-- JS files --}}
<script>
  // Pass Laravel auth data to JS
  window.LARAVEL = {
    role: '{{ Auth::user()->role }}',
    userName: '{{ Auth::user()->name }}',
    csrfToken: '{{ csrf_token() }}',
  };
</script>
<script src="{{ asset('js/config.js') }}"></script>
<script src="{{ asset('js/pages.merchant.js') }}"></script>
<script src="{{ asset('js/pages.merchant.analytics.js') }}"></script>
<script src="{{ asset('js/pages.admin.js') }}"></script>
<script src="{{ asset('js/pages.admin.management.js') }}"></script>
<script src="{{ asset('js/pages.orders.js') }}"></script>
<script src="{{ asset('js/pages.user.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
  // Override APP.role dari localStorage ke Laravel session
  APP.role = window.LARAVEL.role;

  // Override logout() — pakai Laravel POST bukan localStorage
  function logout() {
    document.getElementById('logout-form').submit();
  }

  document.addEventListener('DOMContentLoaded', function () {
    buildSidebar();
    navigate('dashboard');
  });
</script>
@yield('extra-js')
</body>
</html>
