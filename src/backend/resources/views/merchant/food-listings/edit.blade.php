{{--
    resources/views/merchant/food-listings/edit.blade.php
    Data dari: Merchant\FoodListingController@edit
    Variabel: $listing (FoodListing), $categories (Collection), $profile (MerchantProfile)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu — EcoEats Merchant</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#f2ede4; --white:#fff;
            --error:#a13a2a;
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

        .flash { padding:.75rem 1rem; border-radius:10px; font-size:.83rem; margin-bottom:1.25rem; }
        .flash-error { background:rgba(180,60,60,.08); border:1px solid rgba(180,60,60,.2); color:#a03030; }

        .form-grid { display:grid; grid-template-columns:2fr 1fr; gap:1.5rem; }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.5rem; }
        .panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-body { padding:1.5rem; display:flex; flex-direction:column; gap:1.1rem; }

        .form-group { display:flex; flex-direction:column; gap:.4rem; }
        .form-group.row-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .form-label { font-size:.76rem; font-weight:600; color:var(--olive); }
        .form-input, .form-select, .form-textarea {
            padding:.62rem .9rem; font-family:var(--font-b); font-size:.85rem; color:var(--charcoal);
            background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1);
            border-radius:9px; outline:none; transition:border-color .2s, background .2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color:var(--laurel); background:var(--white); box-shadow:0 0 0 3px rgba(169,179,136,.15); }
        .form-input.is-invalid, .form-select.is-invalid { border-color:var(--error); }
        .form-textarea { resize:vertical; min-height:80px; }
        .field-err { font-size:.74rem; color:var(--error); }

        /* Current photo */
        .photo-current { border-radius:10px; overflow:hidden; margin-bottom:.75rem; }
        .photo-current img { width:100%; height:160px; object-fit:cover; display:block; }
        .photo-no-img { height:100px; background:linear-gradient(135deg, var(--laurel), var(--olive)); display:flex; align-items:center; justify-content:center; font-size:2rem; border-radius:10px; margin-bottom:.75rem; }
        .photo-label { font-size:.73rem; color:var(--camel); margin-bottom:.5rem; font-weight:600; }

        .photo-drop { border:2px dashed rgba(56,44,35,.15); border-radius:10px; padding:1.25rem; text-align:center; cursor:pointer; transition:all .2s; position:relative; }
        .photo-drop:hover { border-color:var(--laurel); background:rgba(169,179,136,.05); }
        .photo-drop input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; }
        .photo-drop-text { font-size:.77rem; color:var(--camel); }
        .photo-drop-text strong { color:var(--olive); }
        #photo-preview { width:100%; border-radius:8px; margin-top:.75rem; display:none; max-height:180px; object-fit:cover; }

        .form-actions { padding:1.25rem 1.5rem; border-top:1px solid rgba(56,44,35,.06); display:flex; gap:.75rem; }
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
        <div class="sb-sub">{{ $profile?->business_name ?? '' }}</div>
    </div>
    <nav class="sb-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('merchant.dashboard') }}" class="nav-item">📊 Dashboard</a>
        <a href="{{ route('merchant.listings.index') }}" class="nav-item active">🍱 Menu Surplus</a>
        <div class="nav-label">Akun</div>
        <a href="{{ route('merchant.profile.edit') }}" class="nav-item">🏪 Profil Usaha</a>
        <div class="nav-label">Segera Hadir</div>
        <span class="nav-item" style="opacity:.35; cursor:default;">📋 Pesanan Masuk</span>
    </nav>
    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf <button type="submit" class="btn-logout">↩ Keluar</button>
        </form>
    </div>
</aside>

<main class="main">
    <a href="{{ route('merchant.listings.index') }}" class="back-link">← Kembali ke Menu Surplus</a>

    @if($errors->any())
    <div class="flash flash-error">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="pg-head">
        <h1>Edit Menu Surplus</h1>
        <div class="pg-crumb">EcoEats › Merchant › Menu Surplus › Edit</div>
    </div>

    <form method="POST" action="{{ route('merchant.listings.update', $listing) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            {{-- Kolom kiri --}}
            <div>
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Informasi Menu</div></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="form-label">Nama Menu</label>
                            <input class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   type="text" name="name" value="{{ old('name', $listing->name) }}">
                            @error('name')<div class="field-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <select class="form-select {{ $errors->has('category_id') ? 'is-invalid' : '' }}" name="category_id">
                                <option value="">— Pilih kategori —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $listing->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="field-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-textarea" name="description">{{ old('description', $listing->description) }}</textarea>
                        </div>
                        <div class="form-group row-2">
                            <div>
                                <label class="form-label">Harga Normal</label>
                                <input class="form-input {{ $errors->has('original_price') ? 'is-invalid' : '' }}"
                                       type="number" name="original_price" step="500" min="0"
                                       value="{{ old('original_price', $listing->original_price) }}">
                                @error('original_price')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label">Harga Surplus</label>
                                <input class="form-input {{ $errors->has('discount_price') ? 'is-invalid' : '' }}"
                                       type="number" name="discount_price" step="500" min="0"
                                       value="{{ old('discount_price', $listing->discount_price) }}">
                                @error('discount_price')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-group row-2">
                            <div>
                                <label class="form-label">Stok</label>
                                <input class="form-input {{ $errors->has('stock_qty') ? 'is-invalid' : '' }}"
                                       type="number" name="stock_qty" min="0"
                                       value="{{ old('stock_qty', $listing->stock_qty) }}">
                                @error('stock_qty')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    @foreach(['available'=>'Tersedia','unavailable'=>'Nonaktif','sold_out'=>'Habis'] as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ old('status', $listing->status) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row-2">
                            <div>
                                <label class="form-label">Mulai Pickup</label>
                                <input class="form-input {{ $errors->has('pickup_start') ? 'is-invalid' : '' }}"
                                       type="datetime-local" name="pickup_start"
                                       value="{{ old('pickup_start', $listing->pickup_start?->format('Y-m-d\TH:i')) }}">
                                @error('pickup_start')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label">Batas Pickup</label>
                                <input class="form-input {{ $errors->has('pickup_end') ? 'is-invalid' : '' }}"
                                       type="datetime-local" name="pickup_end"
                                       value="{{ old('pickup_end', $listing->pickup_end?->format('Y-m-d\TH:i')) }}">
                                @error('pickup_end')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <a href="{{ route('merchant.listings.index') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan: foto --}}
            <div>
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Foto Menu</div></div>
                    <div class="panel-body">
                        @if($listing->photo_url)
                        <div class="photo-current">
                            <img src="{{ asset('storage/'.$listing->photo_url) }}" alt="Foto saat ini">
                        </div>
                        <div class="photo-label">Foto saat ini — upload baru untuk mengganti</div>
                        @else
                        <div class="photo-no-img">🍽</div>
                        <div class="photo-label">Belum ada foto</div>
                        @endif

                        <label class="photo-drop" for="photo-input">
                            <div class="photo-drop-text">
                                <strong>{{ $listing->photo_url ? 'Ganti foto' : 'Upload foto' }}</strong><br>
                                JPG, PNG, WebP (maks. 2MB)
                            </div>
                            <input type="file" id="photo-input" name="photo" accept="image/*"
                                   onchange="previewPhoto(this)">
                        </label>
                        <img id="photo-preview" src="" alt="Preview foto baru">
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
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>