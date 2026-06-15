@extends('layouts.merchant')
@section('title', 'Tambah Menu Surplus')
@section('active_nav', 'merchant.listings')

@section('styles')
<style>
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
    .form-input, .form-select, .form-textarea { width:100%; padding:.62rem .9rem; font-family:var(--font-b); font-size:.85rem; color:var(--charcoal); background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1); border-radius:9px; outline:none; transition:border-color .2s, background .2s; }
    .form-input:focus, .form-select:focus, .form-textarea:focus { border-color:var(--laurel); background:var(--white); box-shadow:0 0 0 3px rgba(169,179,136,.15); }
    .form-input.is-invalid, .form-select.is-invalid { border-color:#c0392b; }
    .form-textarea { resize:vertical; min-height:80px; }
    .field-err { font-size:.74rem; color:#a03030; margin-top:.1rem; }
    .photo-drop { border:2px dashed rgba(56,44,35,.15); border-radius:10px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s, background .2s; position:relative; }
    .photo-drop:hover { border-color:var(--laurel); background:rgba(169,179,136,.05); }
    .photo-drop input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .photo-drop-icon { font-size:1.75rem; margin-bottom:.4rem; }
    .photo-drop-text { font-size:.78rem; color:var(--camel); }
    .photo-drop-text strong { color:var(--olive); }
    #photo-preview { width:100%; border-radius:8px; margin-top:.75rem; display:none; max-height:180px; object-fit:cover; }
    .form-actions { display:flex; gap:.75rem; padding:1.25rem 1.5rem; border-top:1px solid rgba(56,44,35,.06); }
    .btn-submit { flex:1; padding:.7rem; background:var(--camel); color:white; border:none; border-radius:9px; font-family:var(--font-b); font-size:.9rem; font-weight:700; cursor:pointer; transition:background .2s; }
    .btn-submit:hover { background:var(--charcoal); }
    .btn-cancel { padding:.7rem 1.25rem; background:transparent; color:rgba(56,44,35,.5); border:1.5px solid rgba(56,44,35,.12); border-radius:9px; font-family:var(--font-b); font-size:.9rem; font-weight:600; cursor:pointer; transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; }
    .btn-cancel:hover { border-color:var(--camel); color:var(--camel); }
    @media(max-width:860px) { .form-grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')

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

@endsection

@section('scripts')
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
@endsection