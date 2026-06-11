{{--
    resources/views/merchant/food-listings/create.blade.php
    Data dari: App\Http\Controllers\Merchant\FoodListingController@create
    Variabel: $categories (Collection), $profile (MerchantProfile)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu Surplus — EcoEats Merchant</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#f2ede4; --white:#fff;
            --font-d:'Fraunces',serif; --font-b:'Plus Jakarta Sans',sans-serif;
            --shadow:0 1px 3px rgba(56,44,35,.06),0 4px 16px rgba(56,44,35,.07);
        }
        body { font-family:var(--font-b); background:var(--bg); color:var(--charcoal); display:flex; min-height:100vh; }

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

        .main { margin-left:230px; flex:1; padding:2rem 2.25rem 4rem; }
        .back-link { display:inline-flex; align-items:center; gap:.4rem; color:var(--camel); font-size:.8rem; font-weight:600; text-decoration:none; margin-bottom:1.25rem; transition:color .15s; }
        .back-link:hover { color:var(--olive); }
        .pg-head { margin-bottom:1.75rem; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        .form-grid { display:grid; grid-template-columns:2fr 1fr; gap:1.5rem; }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.5rem; }
        .panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-body { padding:1.5rem; display:flex; flex-direction:column; gap:1.1rem; }

        .form-group { display:flex; flex-direction:column; gap:.4rem; }
        .form-group.row-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .form-label { font-size:.76rem; font-weight:600; color:var(--olive); }
        .form-label .req { color:#a03030; }
        .form-input, .form-select, .form-textarea {
            width:100%; padding:.62rem .9rem;
            font-family:var(--font-b); font-size:.85rem; color:var(--charcoal);
            background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1);
            border-radius:9px; outline:none; transition:border-color .2s, background .2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color:var(--laurel); background:var(--white); box-shadow:0 0 0 3px rgba(169,179,136,.15); }
        .form-input.is-invalid, .form-select.is-invalid { border-color:#c0392b; }
        .form-textarea { resize:vertical; min-height:80px; }
        .field-err { font-size:.74rem; color:#a03030; margin-top:.1rem; }
        .form-hint { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        /* Photo upload */
        .photo-drop {
            border:2px dashed rgba(56,44,35,.15); border-radius:10px;
            padding:1.5rem; text-align:center; cursor:pointer;
            transition:border-color .2s, background .2s;
            position:relative;
        }
        .photo-drop:hover { border-color:var(--laurel); background:rgba(169,179,136,.05); }
        .photo-drop input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .photo-drop-icon { font-size:1.75rem; margin-bottom:.4rem; }
        .photo-drop-text { font-size:.78rem; color:var(--camel); }
        .photo-drop-text strong { color:var(--olive); }
        #photo-preview { width:100%; border-radius:8px; margin-top:.75rem; display:none; max-height:180px; object-fit:cover; }

        /* Submit area */
        .form-actions { display:flex; gap:.75rem; padding:1.25rem 1.5rem; border-top:1px solid rgba(56,44,35,.06); }
        .btn-submit { flex:1; padding:.7rem; background:var(--camel); color:white; border:none; border-radius:9px; font-family:var(--font-b); font-size:.9rem; font-weight:700; cursor:pointer; transition:background .2s; }
        .btn-submit:hover { background:var(--charcoal); }
        .btn-cancel { padding:.7rem 1.25rem; background:transparent; color:rgba(56,44,35,.5); border:1.5px solid rgba(56,44,35,.12); border-radius:9px; font-family:var(--font-b); font-size:.9rem; font-weight:600; cursor:pointer; transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; }
        .btn-cancel:hover { border-color:var(--camel); color:var(--camel); }

        @media(max-width:860px) { .form-grid { grid-template-columns:1fr; } .sidebar { display:none; } .main { margin-left:0; } }
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
        <a href="{{ route('merchant.listings.index') }}" class="nav-item active">🍱 Menu Surplus</a>
        <a href="{{ route('merchant.orders.index') }}" class="nav-item">📋 Pesanan Masuk</a>
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

<main class="main">
    <a href="{{ route('merchant.listings.index') }}" class="back-link">← Kembali ke Menu Surplus</a>

    <div class="pg-head">
        <h1>Tambah Menu Surplus</h1>
        <div class="pg-crumb">EcoEats › Merchant › Menu Surplus › Tambah</div>
    </div>

    <form method="POST" action="{{ route('merchant.listings.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            {{-- Kolom kiri: info utama --}}
            <div>
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Informasi Menu</div></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="form-label">Nama Menu <span class="req">*</span></label>
                            <input class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   type="text" name="name" value="{{ old('name') }}"
                                   placeholder="contoh: Nasi Ayam Surplus">
                            @error('name')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kategori <span class="req">*</span></label>
                            <select class="form-select {{ $errors->has('category_id') ? 'is-invalid' : '' }}" name="category_id">
                                <option value="">— Pilih kategori —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-textarea" name="description"
                                      placeholder="Ceritakan isi menu, kondisi, dll. (opsional)">{{ old('description') }}</textarea>
                            @error('description')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group row-2">
                            <div>
                                <label class="form-label">Harga Normal <span class="req">*</span></label>
                                <input class="form-input {{ $errors->has('original_price') ? 'is-invalid' : '' }}"
                                       type="number" name="original_price" value="{{ old('original_price') }}"
                                       min="0" step="500" placeholder="25000">
                                @error('original_price')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label">Harga Surplus <span class="req">*</span></label>
                                <input class="form-input {{ $errors->has('discount_price') ? 'is-invalid' : '' }}"
                                       type="number" name="discount_price" value="{{ old('discount_price') }}"
                                       min="0" step="500" placeholder="15000">
                                @error('discount_price')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row-2">
                            <div>
                                <label class="form-label">Stok Tersedia <span class="req">*</span></label>
                                <input class="form-input {{ $errors->has('stock_qty') ? 'is-invalid' : '' }}"
                                       type="number" name="stock_qty" value="{{ old('stock_qty', 1) }}" min="1">
                                @error('stock_qty')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label">Status Awal</label>
                                <select class="form-select" name="status">
                                    <option value="available" {{ old('status','available')==='available' ? 'selected' : '' }}>Langsung Tersedia</option>
                                    <option value="unavailable" {{ old('status')==='unavailable' ? 'selected' : '' }}>Simpan Draft (Nonaktif)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row-2">
                            <div>
                                <label class="form-label">Mulai Pickup <span class="req">*</span></label>
                                <input class="form-input {{ $errors->has('pickup_start') ? 'is-invalid' : '' }}"
                                       type="datetime-local" name="pickup_start" value="{{ old('pickup_start') }}">
                                @error('pickup_start')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label">Batas Pickup <span class="req">*</span></label>
                                <input class="form-input {{ $errors->has('pickup_end') ? 'is-invalid' : '' }}"
                                       type="datetime-local" name="pickup_end" value="{{ old('pickup_end') }}">
                                @error('pickup_end')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('merchant.listings.index') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-submit">Simpan Menu</button>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan: foto --}}
            <div>
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Foto Menu</div></div>
                    <div class="panel-body">
                        <label class="photo-drop" for="photo-input">
                            <div class="photo-drop-icon">📷</div>
                            <div class="photo-drop-text"><strong>Klik untuk unggah</strong><br>JPG, PNG, WebP (maks. 2MB)</div>
                            <input type="file" id="photo-input" name="photo" accept="image/*"
                                   onchange="previewPhoto(this)">
                        </label>
                        <img id="photo-preview" src="" alt="Preview foto">
                        @error('photo')<div class="field-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
function previewPhoto(input) {
    const preview = document.getElementById('photo-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>