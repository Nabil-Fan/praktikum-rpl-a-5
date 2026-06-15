<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoEats — Temukan Makanan Surplus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --olive:     #5F6F52;
            --laurel:    #A9B388;
            --cornsilk:  #FEFAE0;
            --camel:     #B99470;
            --charcoal:  #382C23;
            --font-d:    'Fraunces', serif;
            --font-b:    'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-b);
            background: var(--cornsilk);
            color: var(--charcoal);
            min-height: 100vh;
        }

        /* ── NAV ── */
        nav {
            position: sticky; top: 0; z-index: 100;
            background: var(--charcoal);
            padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            height: 60px;
        }
        .nav-brand {
            font-family: var(--font-d);
            font-size: 1.4rem;
            color: var(--cornsilk);
            display: flex; align-items: center; gap: .5rem;
        }
        .nav-right { display: flex; align-items: center; gap: 1.25rem; }
        .nav-greeting {
            font-size: .8rem;
            color: var(--laurel);
        }
        .btn-logout {
            font-family: var(--font-b);
            font-size: .78rem;
            font-weight: 600;
            padding: .4rem .9rem;
            background: transparent;
            border: 1.5px solid rgba(254,250,224,.25);
            color: var(--cornsilk);
            border-radius: 999px;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            text-decoration: none;
        }
        .btn-logout:hover { background: rgba(254,250,224,.08); border-color: rgba(254,250,224,.5); }

        /* ── HERO ── */
        .hero {
            background: var(--olive);
            padding: 3.5rem 2rem 5rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(169,179,136,.3) 0%, transparent 65%);
            top: -180px; right: -100px;
            pointer-events: none;
        }
        .hero-inner { max-width: 700px; position: relative; z-index: 1; }
        .hero-label {
            font-size: .7rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--laurel);
            margin-bottom: .75rem;
        }
        .hero h1 {
            font-family: var(--font-d);
            font-size: clamp(2rem, 5vw, 3rem);
            color: var(--cornsilk);
            line-height: 1.1;
            margin-bottom: 1rem;
        }
        .hero h1 em { font-style: italic; color: var(--laurel); }
        .hero p {
            font-size: .95rem;
            color: rgba(254,250,224,.7);
            max-width: 440px;
            line-height: 1.7;
        }

        /* ── SEARCH BAR ── */
        .search-wrapper {
            max-width: 860px;
            margin: -28px auto 0;
            padding: 0 1.5rem;
            position: relative; z-index: 10;
        }
        .search-bar {
            display: flex;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(56,44,35,.15);
            overflow: hidden;
        }
        .search-bar input {
            flex: 1;
            padding: 1rem 1.25rem;
            font-family: var(--font-b);
            font-size: .9rem;
            color: var(--charcoal);
            border: none; outline: none;
            background: transparent;
        }
        .search-bar input::placeholder { color: #bbb; }
        .search-bar button {
            padding: .75rem 1.5rem;
            margin: .4rem .4rem .4rem 0;
            background: var(--olive);
            color: var(--cornsilk);
            border: none; border-radius: 10px;
            font-family: var(--font-b); font-weight: 600; font-size: .85rem;
            cursor: pointer;
            transition: background .2s;
            white-space: nowrap;
        }
        .search-bar button:hover { background: var(--charcoal); }

        /* ── MAIN CONTENT ── */
        .main { max-width: 860px; margin: 0 auto; padding: 2.5rem 1.5rem 4rem; }

        /* Stats strip */
        .stats-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2.5rem;
        }
        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex; align-items: center; gap: 1rem;
            box-shadow: 0 2px 8px rgba(56,44,35,.06);
        }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .stat-icon.green  { background: rgba(95,111,82,.12); }
        .stat-icon.camel  { background: rgba(185,148,112,.12); }
        .stat-icon.laurel { background: rgba(169,179,136,.15); }
        .stat-label { font-size: .72rem; color: var(--camel); font-weight: 500; margin-bottom: .2rem; }
        .stat-value { font-family: var(--font-d); font-size: 1.4rem; color: var(--charcoal); font-weight: 700; }

        /* Section header */
        .section-header {
            display: flex; align-items: baseline; justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .section-title {
            font-family: var(--font-d);
            font-size: 1.3rem;
            color: var(--charcoal);
        }
        .section-link { font-size: .8rem; color: var(--olive); text-decoration: none; font-weight: 600; }
        .section-link:hover { text-decoration: underline; }

        /* Category chips */
        .chips { display: flex; gap: .6rem; flex-wrap: wrap; margin-bottom: 2rem; }
        .chip {
            padding: .35rem .85rem;
            border-radius: 999px;
            font-size: .78rem; font-weight: 600;
            border: 1.5px solid var(--laurel);
            color: var(--olive);
            background: transparent;
            cursor: pointer;
            transition: background .2s, color .2s;
        }
        .chip:hover, .chip.active {
            background: var(--olive);
            color: var(--cornsilk);
            border-color: var(--olive);
        }

        /* Food cards grid */
        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.25rem;
        }
        .food-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(56,44,35,.06);
            transition: transform .2s, box-shadow .2s;
            cursor: pointer;
        }
        .food-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(56,44,35,.12); }
        .food-thumb {
            height: 140px;
            background: var(--laurel);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem;
            position: relative;
        }
        .food-thumb.t1 { background: linear-gradient(135deg, #c8d5b9, #a9b388); }
        .food-thumb.t2 { background: linear-gradient(135deg, #ddd0b8, #b99470); }
        .food-thumb.t3 { background: linear-gradient(135deg, #b3c2a0, #5f6f52); }
        .food-thumb.t4 { background: linear-gradient(135deg, #e8dfc8, #cfc4a0); }
        .badge-discount {
            position: absolute; top: .6rem; left: .6rem;
            background: var(--olive);
            color: var(--cornsilk);
            font-size: .65rem; font-weight: 700; letter-spacing: .05em;
            padding: .25rem .5rem; border-radius: 6px;
        }
        .food-info { padding: .9rem 1rem; }
        .food-merchant { font-size: .7rem; color: var(--camel); font-weight: 600; letter-spacing: .05em; text-transform: uppercase; margin-bottom: .3rem; }
        .food-name { font-size: .9rem; font-weight: 600; color: var(--charcoal); margin-bottom: .5rem; line-height: 1.3; }
        .food-footer { display: flex; align-items: center; justify-content: space-between; }
        .food-price-new { font-family: var(--font-d); font-size: 1.05rem; color: var(--olive); font-weight: 700; }
        .food-price-old { font-size: .75rem; color: #bbb; text-decoration: line-through; }
        .food-stock { font-size: .72rem; color: var(--camel); }

        /* Empty state */
        .empty {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--camel);
        }
        .empty-icon { font-size: 3rem; margin-bottom: 1rem; opacity: .5; }
        .empty p { font-size: .9rem; line-height: 1.6; }

        @media (max-width: 600px) {
            .stats-strip { grid-template-columns: 1fr 1fr; }
            .stats-strip .stat-card:last-child { grid-column: span 2; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">🌿 EcoEats</div>
    <div class="nav-right">
        <span class="nav-greeting">Halo, {{ auth()->user()->name }} 👋</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">Keluar</button>
        </form>
    </div>
</nav>

<div class="hero">
    <div class="hero-inner">
        <p class="hero-label">🌿 Selamat datang di EcoEats</p>
        <h1>Makanan enak,<br><em>harga hemat.</em></h1>
        <p>Temukan makanan surplus dari merchant terpercaya di Solo sebelum habis hari ini.</p>
    </div>
</div>

<div class="search-wrapper">
    <div class="search-bar">
        <input type="text" placeholder="Cari makanan atau nama merchant…">
        <button>🔍 Cari</button>
    </div>
</div>

<div class="main">

    <div class="stats-strip">
        <div class="stat-card">
            <div class="stat-icon green">🍱</div>
            <div>
                <div class="stat-label">Menu Tersedia</div>
                <div class="stat-value">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon camel">🏪</div>
            <div>
                <div class="stat-label">Merchant Aktif</div>
                <div class="stat-value">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon laurel">✅</div>
            <div>
                <div class="stat-label">Pesanan Saya</div>
                <div class="stat-value">—</div>
            </div>
        </div>
    </div>

    <div class="section-header">
        <h2 class="section-title">Kategori</h2>
    </div>
    <div class="chips">
        <button class="chip active">Semua</button>
        <button class="chip">🍚 Nasi & Mie</button>
        <button class="chip">🍞 Roti & Kue</button>
        <button class="chip">🥤 Minuman</button>
        <button class="chip">🍗 Lauk & Snack</button>
    </div>

    <div class="section-header">
        <h2 class="section-title">Tersedia Hari Ini</h2>
        <a href="#" class="section-link">Lihat semua →</a>
    </div>

    <div class="empty">
        <div class="empty-icon">🍽</div>
        <p>Belum ada listing makanan surplus hari ini.<br>Cek lagi nanti ya!</p>
    </div>

</div>

</body>
</html>