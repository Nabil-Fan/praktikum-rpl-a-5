{{--
    resources/views/merchant/profile/edit.blade.php
    Data dari: Merchant\ProfileController@edit
    Variabel: $profile (MerchantProfile)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Usaha — EcoEats Merchant</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .flash-success { background:rgba(95,111,82,.1); border:1px solid rgba(95,111,82,.25); color:var(--olive); }
        .flash-error   { background:rgba(180,60,60,.08); border:1px solid rgba(180,60,60,.2); color:#a03030; }

        .form-wrap { max-width:680px; }
        .grid-2 { display:grid; grid-template-columns:2fr 1fr; gap:1.5rem; }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.25rem; }
        .panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-body { padding:1.5rem; display:flex; flex-direction:column; gap:1rem; }

        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .form-group { display:flex; flex-direction:column; gap:.38rem; }
        .form-label { font-size:.76rem; font-weight:600; color:var(--olive); }
        .form-label .opt { font-weight:400; color:var(--camel); }
        .form-input, .form-textarea {
            padding:.62rem .9rem; font-family:var(--font-b); font-size:.85rem; color:var(--charcoal);
            background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1);
            border-radius:9px; outline:none; transition:border-color .2s, background .2s;
        }
        .form-input:focus, .form-textarea:focus { border-color:var(--laurel); background:var(--white); box-shadow:0 0 0 3px rgba(169,179,136,.15); }
        .form-input.is-invalid { border-color:var(--error); }
        .form-textarea { resize:vertical; min-height:80px; }
        .field-err { font-size:.74rem; color:var(--error); }

        /* Verification status badge */
        .ver-badge { display:inline-flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:10px; font-size:.8rem; font-weight:600; margin-bottom:1rem; }
        .ver-pending  { background:rgba(185,148,112,.12); color:#7a4e20; border:1.5px solid rgba(185,148,112,.3); }
        .ver-approved { background:rgba(95,111,82,.1); color:var(--olive); border:1.5px solid rgba(95,111,82,.25); }
        .ver-rejected { background:rgba(180,60,60,.08); color:#a03030; border:1.5px solid rgba(180,60,60,.2); }
        .ver-note { font-size:.74rem; color:var(--camel); margin-top:.35rem; line-height:1.5; }

        /* Doc upload */
        .doc-current { display:flex; align-items:center; justify-content:space-between; padding:.6rem .85rem; background:rgba(95,111,82,.06); border:1.5px solid rgba(95,111,82,.15); border-radius:8px; margin-bottom:.5rem; font-size:.78rem; }
        .doc-current-name { color:var(--olive); font-weight:600; }
        .doc-current-link { color:var(--olive); text-decoration:none; font-size:.73rem; }
        .doc-current-link:hover { text-decoration:underline; }
        .doc-upload { border:2px dashed rgba(56,44,35,.13); border-radius:9px; padding:1rem; text-align:center; cursor:pointer; transition:all .2s; position:relative; }
        .doc-upload:hover { border-color:var(--laurel); background:rgba(169,179,136,.05); }
        .doc-upload input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; }
        .doc-upload-text { font-size:.77rem; color:var(--camel); }
        .doc-upload-text strong { color:var(--olive); }
        .doc-file-name { font-size:.74rem; color:var(--olive); font-weight:600; margin-top:.4rem; display:none; }

        /* Doc warning */
        .doc-warning { background:rgba(185,148,112,.08); border:1.5px solid rgba(185,148,112,.25); border-radius:9px; padding:.75rem 1rem; font-size:.78rem; color:var(--camel); display:flex; gap:.5rem; }

        .panel-actions { padding:1.25rem 1.5rem; border-top:1px solid rgba(56,44,35,.06); display:flex; gap:.75rem; }
        .btn-save { flex:1; padding:.7rem; background:var(--camel); color:white; border:none; border-radius:9px; font-family:var(--font-b); font-size:.88rem; font-weight:700; cursor:pointer; transition:background .2s; }
        .btn-save:hover { background:var(--charcoal); }
        .btn-cancel { padding:.7rem 1.25rem; background:transparent; color:rgba(56,44,35,.5); border:1.5px solid rgba(56,44,35,.12); border-radius:9px; font-family:var(--font-b); font-size:.88rem; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; transition:all .2s; }
        .btn-cancel:hover { border-color:var(--camel); color:var(--camel); }

        @media(max-width:860px) { .grid-2 { grid-template-columns:1fr; } .sidebar { display:none; } .main { margin-left:0; } .form-row { grid-template-columns:1fr; } }
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
        <a href="{{ route('merchant.listings.index') }}" class="nav-item">🍱 Menu Surplus</a>
        <div class="nav-label">Akun</div>
        <a href="{{ route('merchant.profile.edit') }}" class="nav-item active">🏪 Profil Usaha</a>
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
    <a href="{{ route('merchant.dashboard') }}" class="back-link">← Kembali ke Dashboard</a>

    @if(session('success'))
        <div class="flash flash-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">⚠️ {{ session('error') }}</div>
    @endif

    <div class="pg-head">
        <h1>Edit Profil Usaha</h1>
        <div class="pg-crumb">EcoEats › Merchant › Profil Usaha</div>
    </div>

    <div class="form-wrap">
        {{-- Status verifikasi --}}
        <div class="ver-badge ver-{{ $profile->verification_status }}">
            @if($profile->verification_status === 'approved')
                ✅ Akun Terverifikasi
            @elseif($profile->verification_status === 'rejected')
                ❌ Verifikasi Ditolak
            @else
                ⏳ Menunggu Verifikasi Admin
            @endif
        </div>
        @if($profile->verification_status === 'rejected')
        <div class="ver-note" style="margin-bottom:1.25rem;">
            Pengajuan Anda sebelumnya ditolak. Upload ulang dokumen yang benar, lalu simpan untuk mengirimkan ulang ke admin.
        </div>
        @endif

        <form method="POST" action="{{ route('merchant.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Info usaha --}}
            <div class="panel">
                <div class="panel-head"><div class="panel-title">Informasi Usaha</div></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label">Nama Usaha</label>
                        <input class="form-input {{ $errors->has('business_name') ? 'is-invalid' : '' }}"
                               type="text" name="business_name"
                               value="{{ old('business_name', $profile->business_name) }}">
                        @error('business_name')<div class="field-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-textarea {{ $errors->has('business_address') ? 'is-invalid' : '' }}"
                                  name="business_address">{{ old('business_address', $profile->business_address) }}</textarea>
                        @error('business_address')<div class="field-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Latitude</label>
                            <input class="form-input {{ $errors->has('latitude') ? 'is-invalid' : '' }}"
                                   type="number" name="latitude" step="any"
                                   value="{{ old('latitude', $profile->latitude) }}">
                            @error('latitude')<div class="field-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude</label>
                            <input class="form-input {{ $errors->has('longitude') ? 'is-invalid' : '' }}"
                                   type="number" name="longitude" step="any"
                                   value="{{ old('longitude', $profile->longitude) }}">
                            @error('longitude')<div class="field-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dokumen --}}
            <div class="panel">
                <div class="panel-head"><div class="panel-title">Dokumen Legalitas</div></div>
                <div class="panel-body">
                    <div class="doc-warning">
                        <span>⚠️</span>
                        <span>Jika Anda mengganti dokumen, status verifikasi akan kembali ke <strong>pending</strong> dan perlu ditinjau ulang oleh admin.</span>
                    </div>

                    {{-- Business license --}}
                    <div class="form-group">
                        <label class="form-label">Surat Izin Usaha <span class="opt">(opsional)</span></label>
                        @if($profile->business_license_url)
                        <div class="doc-current">
                            <span class="doc-current-name">📋 Dokumen saat ini tersimpan</span>
                            <a href="{{ asset('storage/'.$profile->business_license_url) }}"
                               target="_blank" class="doc-current-link">Buka ↗</a>
                        </div>
                        @endif
                        <label class="doc-upload" for="business_license">
                            <div class="doc-upload-text">
                                <strong>{{ $profile->business_license_url ? 'Ganti dokumen' : 'Upload dokumen' }}</strong>
                                — JPG, PNG, atau PDF (maks. 4MB)
                            </div>
                            <div class="doc-file-name" id="license-fname"></div>
                            <input type="file" id="business_license" name="business_license"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   onchange="showFName(this,'license-fname')">
                        </label>
                        @error('business_license')<div class="field-err">{{ $message }}</div>@enderror
                    </div>

                    {{-- Halal cert --}}
                    <div class="form-group">
                        <label class="form-label">Sertifikat Halal <span class="opt">(opsional)</span></label>
                        @if($profile->halal_cert_url)
                        <div class="doc-current">
                            <span class="doc-current-name">✅ Dokumen saat ini tersimpan</span>
                            <a href="{{ asset('storage/'.$profile->halal_cert_url) }}"
                               target="_blank" class="doc-current-link">Buka ↗</a>
                        </div>
                        @endif
                        <label class="doc-upload" for="halal_cert">
                            <div class="doc-upload-text">
                                <strong>{{ $profile->halal_cert_url ? 'Ganti dokumen' : 'Upload dokumen' }}</strong>
                                — JPG, PNG, atau PDF (maks. 4MB)
                            </div>
                            <div class="doc-file-name" id="halal-fname"></div>
                            <input type="file" id="halal_cert" name="halal_cert"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   onchange="showFName(this,'halal-fname')">
                        </label>
                        @error('halal_cert')<div class="field-err">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="panel-actions">
                    <a href="{{ route('merchant.dashboard') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
function showFName(input, targetId) {
    const el = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        el.textContent = '✓ ' + input.files[0].name;
        el.style.display = 'block';
    }
}
</script>
</body>
</html>