{{--
    resources/views/admin/accounts/create.blade.php
    Data dari: Admin\AccountController@create
    Form dinamis: field merchant muncul jika role = merchant dipilih
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Akun — EcoEats Admin</title>
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

        header { background:var(--charcoal); height:58px; display:flex; align-items:center; padding:0 1.75rem; gap:1.25rem; position:sticky; top:0; z-index:100; }
        .h-brand { font-family:var(--font-d); font-size:1.15rem; color:var(--cornsilk); }
        .h-sep { width:1px; height:18px; background:rgba(254,250,224,.12); }
        .h-label { font-size:.68rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--camel); }
        .h-right { margin-left:auto; display:flex; align-items:center; gap:1rem; }
        .h-user strong { color:var(--cornsilk); } .h-user { font-size:.8rem; color:rgba(254,250,224,.65); }
        .btn-out { padding:.32rem .8rem; background:transparent; border:1.5px solid rgba(254,250,224,.18); color:rgba(254,250,224,.55); border-radius:999px; font-family:var(--font-b); font-size:.73rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-out:hover { border-color:rgba(254,250,224,.45); color:var(--cornsilk); }

        .layout { display:flex; min-height:calc(100vh - 58px); }
        .sidenav { width:210px; background:var(--white); border-right:1px solid rgba(56,44,35,.07); padding:1.25rem 0; position:sticky; top:58px; height:calc(100vh - 58px); overflow-y:auto; flex-shrink:0; }
        .sn-label { font-size:.6rem; font-weight:700; letter-spacing:.13em; text-transform:uppercase; color:rgba(56,44,35,.28); padding:.65rem 1.25rem .2rem; }
        .sn-item { display:flex; align-items:center; gap:.6rem; padding:.52rem 1.25rem; font-size:.8rem; color:rgba(56,44,35,.5); text-decoration:none; border-left:2.5px solid transparent; transition:all .15s; }
        .sn-item:hover { color:var(--charcoal); background:rgba(56,44,35,.025); }
        .sn-item.active { color:var(--charcoal); border-left-color:var(--olive); background:rgba(95,111,82,.06); font-weight:600; }

        .content { flex:1; padding:2rem 2.25rem 4rem; }
        .back-link { display:inline-flex; align-items:center; gap:.4rem; color:var(--camel); font-size:.8rem; font-weight:600; text-decoration:none; margin-bottom:1.25rem; transition:color .15s; }
        .back-link:hover { color:var(--olive); }
        .pg-head { margin-bottom:1.75rem; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        .form-wrap { max-width:640px; }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.25rem; }
        .panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-body { padding:1.5rem; display:flex; flex-direction:column; gap:1rem; }

        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .form-group { display:flex; flex-direction:column; gap:.38rem; }
        .form-label { font-size:.76rem; font-weight:600; color:var(--olive); }
        .form-label .opt { font-weight:400; color:var(--camel); }
        .form-input, .form-select, .form-textarea {
            padding:.62rem .9rem; font-family:var(--font-b); font-size:.85rem; color:var(--charcoal);
            background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1);
            border-radius:9px; outline:none; transition:border-color .2s, background .2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color:var(--laurel); background:var(--white); box-shadow:0 0 0 3px rgba(169,179,136,.15); }
        .form-input.is-invalid, .form-select.is-invalid { border-color:var(--error); }
        .form-textarea { resize:vertical; min-height:80px; }
        .field-err { font-size:.74rem; color:var(--error); }

        /* Role selector */
        .role-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:.75rem; }
        .role-card { position:relative; }
        .role-card input[type="radio"] { position:absolute; opacity:0; }
        .role-card label {
            display:flex; flex-direction:column; align-items:center; gap:.35rem;
            padding:.85rem .5rem; border-radius:11px;
            border:2px solid rgba(56,44,35,.1); background:rgba(56,44,35,.02);
            cursor:pointer; transition:all .15s; text-align:center;
        }
        .role-card label:hover { border-color:var(--laurel); background:rgba(169,179,136,.07); }
        .role-card input:checked + label { border-color:var(--olive); background:rgba(95,111,82,.08); }
        .role-icon { font-size:1.4rem; }
        .role-name { font-size:.75rem; font-weight:700; color:var(--charcoal); }
        .role-desc { font-size:.67rem; color:var(--camel); line-height:1.3; }

        /* Merchant fields — hidden by default */
        #merchant-fields { display:none; }
        #merchant-fields.show { display:block; }

        .merchant-notice {
            background:rgba(185,148,112,.09); border:1.5px solid rgba(185,148,112,.25);
            border-radius:10px; padding:.85rem 1rem; font-size:.79rem; color:var(--camel);
            display:flex; gap:.5rem; align-items:flex-start; margin-bottom:1rem;
        }

        .panel-actions { padding:1.25rem 1.5rem; border-top:1px solid rgba(56,44,35,.06); display:flex; gap:.75rem; }
        .btn-save { flex:1; padding:.7rem; background:var(--olive); color:white; border:none; border-radius:9px; font-family:var(--font-b); font-size:.88rem; font-weight:700; cursor:pointer; transition:background .2s; }
        .btn-save:hover { background:var(--charcoal); }
        .btn-cancel { padding:.7rem 1.25rem; background:transparent; color:rgba(56,44,35,.5); border:1.5px solid rgba(56,44,35,.12); border-radius:9px; font-family:var(--font-b); font-size:.88rem; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; transition:all .2s; }
        .btn-cancel:hover { border-color:var(--camel); color:var(--camel); }

        @media(max-width:600px) { .form-row,.role-cards { grid-template-columns:1fr; } }
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
    <aside class="sidenav">
        <div class="sn-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="sn-item">📊 Dashboard</a>
        <div class="sn-label">Manajemen</div>
        <a href="{{ route('admin.merchants.index') }}" class="sn-item">🏪 Verifikasi Merchant</a>
        <a href="{{ route('admin.users.index') }}" class="sn-item">👥 Pengguna</a>
        <a href="{{ route('admin.food-listings.index') }}" class="sn-item">🍱 Food Listing</a>
        <a href="{{ route('admin.orders.index') }}" class="sn-item">📋 Semua Pesanan</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item active">➕ Tambah Akun</a>
    </aside>

    <main class="content">
        <a href="{{ route('admin.users.index') }}" class="back-link">← Kembali ke Pengguna</a>
        <div class="pg-head">
            <h1>Tambah Akun Manual</h1>
            <div class="pg-crumb">EcoEats › Admin › Pengguna › Tambah</div>
        </div>

        <div class="form-wrap">
            <form method="POST" action="{{ route('admin.accounts.store') }}">
                @csrf

                {{-- Role selector --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Pilih Role Akun</div></div>
                    <div class="panel-body">
                        <div class="role-cards">
                            <div class="role-card">
                                <input type="radio" id="role-user" name="role" value="user"
                                       {{ old('role','user') === 'user' ? 'checked' : '' }}
                                       onchange="toggleMerchantFields(this.value)">
                                <label for="role-user">
                                    <span class="role-icon">👤</span>
                                    <span class="role-name">User</span>
                                    <span class="role-desc">Pembeli makanan surplus</span>
                                </label>
                            </div>
                            <div class="role-card">
                                <input type="radio" id="role-merchant" name="role" value="merchant"
                                       {{ old('role') === 'merchant' ? 'checked' : '' }}
                                       onchange="toggleMerchantFields(this.value)">
                                <label for="role-merchant">
                                    <span class="role-icon">🏪</span>
                                    <span class="role-name">Merchant</span>
                                    <span class="role-desc">Penjual makanan surplus</span>
                                </label>
                            </div>
                            <div class="role-card">
                                <input type="radio" id="role-admin" name="role" value="admin"
                                       {{ old('role') === 'admin' ? 'checked' : '' }}
                                       onchange="toggleMerchantFields(this.value)">
                                <label for="role-admin">
                                    <span class="role-icon">🔑</span>
                                    <span class="role-name">Admin</span>
                                    <span class="role-desc">Pengelola platform</span>
                                </label>
                            </div>
                        </div>
                        @error('role')<div class="field-err" style="margin-top:.5rem">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Info akun --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Informasi Akun</div></div>
                    <div class="panel-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       type="text" name="name" value="{{ old('name') }}" placeholder="Nama pengguna">
                                @error('name')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. HP <span class="opt">(opsional)</span></label>
                                <input class="form-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       type="tel" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                                @error('phone')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com">
                            @error('email')<div class="field-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password Awal</label>
                            <input class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   type="password" name="password" placeholder="Minimal 8 karakter">
                            <div class="field-err" style="color:var(--camel); font-size:.72rem">Sampaikan password ini ke pengguna secara aman.</div>
                            @error('password')<div class="field-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Merchant fields (conditional) --}}
                <div id="merchant-fields" class="{{ old('role') === 'merchant' ? 'show' : '' }}">
                    <div class="panel">
                        <div class="panel-head"><div class="panel-title">Profil Usaha Merchant</div></div>
                        <div class="panel-body">
                            <div class="merchant-notice">
                                <span>ℹ️</span>
                                <span>Akun merchant yang dibuat admin otomatis berstatus <strong>approved</strong> — tidak perlu proses verifikasi. Dokumen bisa diunggah merchant sendiri setelah login.</span>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Usaha</label>
                                <input class="form-input {{ $errors->has('business_name') ? 'is-invalid' : '' }}"
                                       type="text" name="business_name" value="{{ old('business_name') }}"
                                       placeholder="Nama restoran/kafe/warung">
                                @error('business_name')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Usaha</label>
                                <textarea class="form-textarea {{ $errors->has('business_address') ? 'is-invalid' : '' }}"
                                          name="business_address" placeholder="Alamat lengkap tempat usaha">{{ old('business_address') }}</textarea>
                                @error('business_address')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Latitude</label>
                                    <input class="form-input {{ $errors->has('latitude') ? 'is-invalid' : '' }}"
                                           type="number" name="latitude" value="{{ old('latitude') }}" step="any" placeholder="-7.5695">
                                    @error('latitude')<div class="field-err">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Longitude</label>
                                    <input class="form-input {{ $errors->has('longitude') ? 'is-invalid' : '' }}"
                                           type="number" name="longitude" value="{{ old('longitude') }}" step="any" placeholder="110.8270">
                                    @error('longitude')<div class="field-err">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-save">Buat Akun</button>
                    </div>
                </div>

            </form>
        </div>
    </main>
</div>

<script>
function toggleMerchantFields(role) {
    const el = document.getElementById('merchant-fields');
    el.classList.toggle('show', role === 'merchant');
}
// Init on load (untuk old() value setelah validation error)
toggleMerchantFields(document.querySelector('input[name="role"]:checked')?.value ?? 'user');
</script>
</body>
</html>