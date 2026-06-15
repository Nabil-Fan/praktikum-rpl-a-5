{{--
    resources/views/admin/categories/index.blade.php
    Data dari: Admin\CategoryController@index
    Variabel: $categories (Collection, with food_listings_count)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori — EcoEats Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#eee9df; --white:#fff;
            --error:#a13a2a;
            --font-d:'Fraunces',serif; --font-b:'Plus Jakarta Sans',sans-serif;
            --shadow:0 1px 3px rgba(56,44,35,.06),0 4px 16px rgba(56,44,35,.07);
        }
        body { font-family:var(--font-b); background:var(--bg); color:var(--charcoal); min-height:100vh; }

        /* ── TOPBAR ── */
        header { background:var(--charcoal); height:58px; display:flex; align-items:center; padding:0 1.75rem; gap:1.25rem; position:sticky; top:0; z-index:100; }
        .h-brand { font-family:var(--font-d); font-size:1.15rem; color:var(--cornsilk); }
        .h-sep { width:1px; height:18px; background:rgba(254,250,224,.12); }
        .h-label { font-size:.68rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--camel); }
        .h-right { margin-left:auto; display:flex; align-items:center; gap:1rem; }
        .h-user { font-size:.8rem; color:rgba(254,250,224,.65); }
        .h-user strong { color:var(--cornsilk); }
        .btn-out { padding:.32rem .8rem; background:transparent; border:1.5px solid rgba(254,250,224,.18); color:rgba(254,250,224,.55); border-radius:999px; font-family:var(--font-b); font-size:.73rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-out:hover { border-color:rgba(254,250,224,.45); color:var(--cornsilk); }

        /* ── LAYOUT ── */
        .layout { display:flex; min-height:calc(100vh - 58px); }

        /* ── SIDENAV ── */
        .sidenav { width:210px; background:var(--white); border-right:1px solid rgba(56,44,35,.07); padding:1.25rem 0; position:sticky; top:58px; height:calc(100vh - 58px); overflow-y:auto; flex-shrink:0; }
        .sn-label { font-size:.6rem; font-weight:700; letter-spacing:.13em; text-transform:uppercase; color:rgba(56,44,35,.28); padding:.65rem 1.25rem .2rem; }
        .sn-item { display:flex; align-items:center; gap:.6rem; padding:.52rem 1.25rem; font-size:.8rem; color:rgba(56,44,35,.5); text-decoration:none; border-left:2.5px solid transparent; transition:all .15s; }
        .sn-item:hover { color:var(--charcoal); background:rgba(56,44,35,.025); }
        .sn-item.active { color:var(--charcoal); border-left-color:var(--olive); background:rgba(95,111,82,.06); font-weight:600; }

        /* ── CONTENT ── */
        .content { flex:1; padding:2rem 2.25rem 4rem; }
        .pg-head { margin-bottom:1.75rem; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        /* Flash */
        .flash { padding:.75rem 1rem; border-radius:10px; font-size:.83rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:.5rem; }
        .flash-success { background:rgba(95,111,82,.1); border:1px solid rgba(95,111,82,.25); color:var(--olive); }
        .flash-error   { background:rgba(161,58,42,.07); border:1px solid rgba(161,58,42,.2); color:var(--error); }

        /* ── GRID LAYOUT ── */
        .grid-layout { display:grid; grid-template-columns:1fr 360px; gap:1.5rem; align-items:start; }

        /* ── PANEL ── */
        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
        .panel-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-count { font-size:.72rem; font-weight:700; color:var(--camel); background:rgba(56,44,35,.06); padding:.2rem .6rem; border-radius:999px; }

        /* ── TABLE ── */
        table { width:100%; border-collapse:collapse; }
        thead tr { background:rgba(56,44,35,.025); }
        th { text-align:left; padding:.6rem 1.25rem; font-size:.67rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:rgba(56,44,35,.38); white-space:nowrap; }
        td { padding:.75rem 1.25rem; font-size:.83rem; color:var(--charcoal); border-top:1px solid rgba(56,44,35,.05); vertical-align:middle; }
        tr:hover td { background:rgba(56,44,35,.012); }

        /* Inline edit row */
        .edit-row td { background:rgba(169,179,136,.06); border-top:2px solid var(--laurel); }
        .edit-row:hover td { background:rgba(169,179,136,.08); }

        /* Count badge */
        .count-badge { display:inline-block; padding:.15rem .55rem; border-radius:999px; font-size:.68rem; font-weight:700; }
        .cb-active { background:rgba(95,111,82,.1); color:var(--olive); }
        .cb-zero   { background:rgba(56,44,35,.06); color:rgba(56,44,35,.4); }

        /* Action buttons */
        .btn-sm { padding:.27rem .65rem; border-radius:6px; font-family:var(--font-b); font-size:.72rem; font-weight:600; cursor:pointer; transition:all .15s; border:1.5px solid transparent; background:transparent; }
        .btn-edit { color:var(--olive); border-color:rgba(95,111,82,.3); }
        .btn-edit:hover { background:rgba(95,111,82,.07); }
        .btn-del  { color:var(--error); border-color:rgba(161,58,42,.25); }
        .btn-del:hover { background:rgba(161,58,42,.06); }
        .btn-cancel { color:rgba(56,44,35,.45); border-color:rgba(56,44,35,.12); }
        .btn-cancel:hover { background:rgba(56,44,35,.04); }
        .btn-save { color:var(--white); background:var(--olive); border-color:var(--olive); padding:.27rem .75rem; }
        .btn-save:hover { background:var(--charcoal); border-color:var(--charcoal); }

        /* Inline edit input */
        .edit-input { padding:.3rem .65rem; font-family:var(--font-b); font-size:.83rem; color:var(--charcoal); background:var(--white); border:1.5px solid var(--laurel); border-radius:7px; outline:none; width:100%; max-width:240px; box-shadow:0 0 0 3px rgba(169,179,136,.15); }
        .field-err { font-size:.72rem; color:var(--error); margin-top:.25rem; }

        /* Empty */
        .empty { text-align:center; padding:3rem 1rem; color:var(--camel); }
        .empty .ei { font-size:2rem; margin-bottom:.6rem; opacity:.4; }
        .empty p { font-size:.82rem; }

        /* ── ADD FORM PANEL ── */
        .add-panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
        .add-panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .add-panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .add-panel-body { padding:1.25rem 1.5rem; }

        .form-group { margin-bottom:.9rem; }
        .form-label { display:block; font-size:.75rem; font-weight:600; color:var(--olive); margin-bottom:.38rem; }
        .form-input { width:100%; padding:.62rem .9rem; font-family:var(--font-b); font-size:.85rem; color:var(--charcoal); background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1); border-radius:9px; outline:none; transition:border-color .2s, background .2s; }
        .form-input:focus { border-color:var(--laurel); background:var(--white); box-shadow:0 0 0 3px rgba(169,179,136,.15); }
        .form-input.is-invalid { border-color:var(--error); }
        .form-hint { font-size:.72rem; color:var(--camel); margin-top:.3rem; }

        .btn-add { width:100%; padding:.7rem; background:var(--olive); color:white; border:none; border-radius:9px; font-family:var(--font-b); font-size:.88rem; font-weight:700; cursor:pointer; transition:background .2s; margin-top:.25rem; }
        .btn-add:hover { background:var(--charcoal); }

        /* Info card */
        .info-card { margin-top:1rem; background:rgba(169,179,136,.1); border:1.5px solid rgba(169,179,136,.25); border-radius:10px; padding:.85rem 1rem; font-size:.77rem; color:var(--olive); line-height:1.6; }
        .info-card strong { display:block; margin-bottom:.2rem; }

        @media(max-width:900px) { .grid-layout { grid-template-columns:1fr; } .sidenav { display:none; } }
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

    {{-- SIDENAV --}}
    <aside class="sidenav">
        <div class="sn-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="sn-item">📊 Dashboard</a>
        <div class="sn-label">Manajemen</div>
        <a href="{{ route('admin.merchants.index') }}" class="sn-item">🏪 Verifikasi Merchant</a>
        <a href="{{ route('admin.users.index') }}" class="sn-item">👥 Pengguna</a>
        <a href="{{ route('admin.food-listings.index') }}" class="sn-item">🍱 Food Listing</a>
        <a href="{{ route('admin.orders.index') }}" class="sn-item">📋 Semua Pesanan</a>
        <a href="{{ route('admin.withdrawals.index') }}" class="sn-item">💰 Penarikan Dana</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item active">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
        <a href="{{ route('admin.map') }}" class="sn-item">🗺 Peta Merchant</a>
    </aside>

    {{-- CONTENT --}}
    <main class="content">

        @if(session('success'))
            <div class="flash flash-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">⚠️ {{ session('error') }}</div>
        @endif

        <div class="pg-head">
            <h1>Manajemen Kategori</h1>
            <div class="pg-crumb">EcoEats › Admin › Kategori</div>
        </div>

        <div class="grid-layout">

            {{-- Kiri: tabel kategori --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">Daftar Kategori</div>
                    <span class="panel-count">{{ $categories->count() }} kategori</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Listing Aktif</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)

                        {{-- Row normal --}}
                        <tr id="row-{{ $cat->id }}" class="{{ $editing == $cat->id ? 'edit-row' : '' }}">
                            <td style="color:var(--camel); font-size:.75rem">{{ $loop->iteration }}</td>

                            {{-- Mode view --}}
                            <td id="cell-name-{{ $cat->id }}">
                                @if($editing != $cat->id)
                                    <strong>{{ $cat->name }}</strong>
                                @else
                                    {{-- Mode edit inline --}}
                                    <form method="POST" action="{{ route('admin.categories.update', $cat) }}" id="form-edit-{{ $cat->id }}">
                                        @csrf @method('PUT')
                                        <input class="edit-input {{ $errors->has('name') && $editing == $cat->id ? 'is-invalid' : '' }}"
                                               type="text" name="name"
                                               value="{{ old('name', $cat->name) }}"
                                               autofocus>
                                        @if($errors->has('name') && $editing == $cat->id)
                                            <div class="field-err">{{ $errors->first('name') }}</div>
                                        @endif
                                    </form>
                                @endif
                            </td>

                            <td>
                                <span class="count-badge {{ $cat->food_listings_count > 0 ? 'cb-active' : 'cb-zero' }}">
                                    {{ $cat->food_listings_count }} listing
                                </span>
                            </td>

                            <td style="font-size:.75rem; color:var(--camel)">
                                {{ $cat->created_at->format('d M Y') }}
                            </td>

                            <td>
                                @if($editing != $cat->id)
                                    {{-- Tombol edit: kirim ke GET dengan ?edit=id --}}
                                    <a href="{{ route('admin.categories.index', ['edit' => $cat->id]) }}"
                                       class="btn-sm btn-edit">Edit</a>

                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                                          style="display:inline"
                                          onsubmit="return confirm('Hapus kategori \'{{ addslashes($cat->name) }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-sm btn-del"
                                                {{ $cat->food_listings_count > 0 ? 'title=Tidak bisa dihapus, masih ada listing aktif' : '' }}>
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    {{-- Mode edit: simpan atau batal --}}
                                    <button type="submit" form="form-edit-{{ $cat->id }}" class="btn-sm btn-save">Simpan</button>
                                    <a href="{{ route('admin.categories.index') }}" class="btn-sm btn-cancel">Batal</a>
                                @endif
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty">
                                    <div class="ei">🏷</div>
                                    <p>Belum ada kategori.<br>Tambahkan kategori pertama di sebelah kanan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Kanan: form tambah --}}
            <div>
                <div class="add-panel">
                    <div class="add-panel-head">
                        <div class="add-panel-title">Tambah Kategori Baru</div>
                    </div>
                    <div class="add-panel-body">
                        <form method="POST" action="{{ route('admin.categories.store') }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="new-name">Nama Kategori</label>
                                <input class="form-input {{ $errors->has('name') && !$editing ? 'is-invalid' : '' }}"
                                       type="text" id="new-name" name="name"
                                       value="{{ !$editing ? old('name') : '' }}"
                                       placeholder="contoh: Nasi & Mie">
                                @if($errors->has('name') && !$editing)
                                    <div class="field-err" style="font-size:.72rem; color:var(--error); margin-top:.3rem">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                                <div class="form-hint">Maksimal 80 karakter. Nama harus unik.</div>
                            </div>
                            <button type="submit" class="btn-add">＋ Tambah Kategori</button>
                        </form>

                        <div class="info-card">
                            <strong>💡 Catatan</strong>
                            Kategori yang masih dipakai oleh listing aktif tidak bisa dihapus. Nonaktifkan atau hapus listing terkait terlebih dahulu.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>