@extends('layouts.merchant')

@section('title', 'Edit Menu — EcoEats Merchant')
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
    .form-input, .form-select, .form-textarea {
        padding:.62rem .9rem; font-family:var(--font-b); font-size:.85rem; color:var(--charcoal);
        background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1);
        border-radius:9px; outline:none; transition:border-color .2s, background .2s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus { border-color:var(--laurel); background:var(--white); box-shadow:0 0 0 3px rgba(169,179,136,.15); }
    .form-input.is-invalid, .form-select.is-invalid { border-color:#a03030; }
    .form-textarea { resize:vertical; min-height:80px; }
    .field-err { font-size:.74rem; color:#a03030; }

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

    @media(max-width:860px) { .form-grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')
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
                        <input type="file" id="photo-input" name="photo" accept="image/*" onchange="previewPhoto(this)">
                    </label>

                    <img id="photo-preview" src="" alt="Preview foto baru">
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
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection