{{--
    resources/views/auth/register-merchant.blade.php
    Dipanggil dari: RegisterController@showMerchantRegister
    Form satu halaman: akun + profil usaha + upload dokumen
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mitra Merchant — EcoEats</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --olive:    #5F6F52;
            --laurel:   #A9B388;
            --cornsilk: #FEFAE0;
            --camel:    #B99470;
            --charcoal: #382C23;
            --bg:       #FEFAE0;
            --white:    #ffffff;
            --error:    #a13a2a;
            --font-d:   'Fraunces', Georgia, serif;
            --font-b:   'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-b);
            background: var(--bg);
            min-height: 100vh;
            padding: 2rem 1.5rem 4rem;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(169,179,136,.18) 0%, transparent 70%);
            top: -150px; right: -150px;
            pointer-events: none;
        }

        .page-wrap {
            max-width: 680px;
            margin: 0 auto;
            animation: fadeUp .5s ease both;
        }
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }

        /* Brand */
        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-logo {
            display: inline-flex; align-items: center; gap: .5rem;
            text-decoration: none; margin-bottom: .4rem;
        }
        .brand-icon { width: 40px; height: 40px; background: var(--olive); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .brand-name { font-family: var(--font-d); font-size: 1.7rem; font-weight: 700; color: var(--charcoal); letter-spacing: -.02em; }
        .brand-tagline { font-size: .78rem; color: var(--camel); letter-spacing: .06em; text-transform: uppercase; }

        /* Progress steps */
        .steps {
            display: flex; align-items: center; justify-content: center;
            gap: 0; margin-bottom: 2rem;
        }
        .step {
            display: flex; align-items: center; gap: .45rem;
            font-size: .72rem; font-weight: 700; color: rgba(56,44,35,.3);
        }
        .step.active { color: var(--olive); }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%;
            border: 2px solid currentColor;
            display: flex; align-items: center; justify-content: center;
            font-size: .68rem; flex-shrink: 0;
        }
        .step-line { width: 40px; height: 2px; background: rgba(56,44,35,.1); margin: 0 .5rem; }

        /* Card */
        .card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 1px 2px rgba(56,44,35,.06), 0 8px 32px rgba(56,44,35,.1), 0 0 0 1px rgba(56,44,35,.04);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .card-head {
            padding: 1.5rem 2rem 1rem;
            border-bottom: 1px solid rgba(56,44,35,.06);
        }
        .section-badge {
            display: inline-flex; align-items: center; gap: .35rem;
            font-size: .65rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            padding: .25rem .65rem; border-radius: 999px;
            background: rgba(95,111,82,.09); color: var(--olive); border: 1px solid rgba(95,111,82,.2);
            margin-bottom: .6rem;
        }
        .card-title { font-family: var(--font-d); font-size: 1.2rem; color: var(--charcoal); }
        .card-sub { font-size: .8rem; color: var(--camel); margin-top: .2rem; }
        .card-body { padding: 1.5rem 2rem; display: flex; flex-direction: column; gap: 1.1rem; }

        /* Form elements */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { display: flex; flex-direction: column; gap: .38rem; }
        .form-label { font-size: .76rem; font-weight: 600; color: var(--olive); }
        .form-label .opt { font-weight: 400; color: var(--camel); }
        .form-input, .form-textarea {
            padding: .68rem .95rem;
            font-family: var(--font-b); font-size: .88rem; color: var(--charcoal);
            background: #f5f0d8; border: 1.5px solid #e8e2c8;
            border-radius: 10px; outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .form-input:focus, .form-textarea:focus {
            border-color: var(--laurel); background: var(--white);
            box-shadow: 0 0 0 3px rgba(169,179,136,.18);
        }
        .form-input.is-invalid { border-color: var(--error); }
        .form-textarea { resize: vertical; min-height: 80px; }
        .field-error { font-size: .74rem; color: var(--error); }
        .form-hint { font-size: .72rem; color: var(--camel); }

        /* Document upload */
        .doc-upload {
            border: 2px dashed rgba(56,44,35,.14); border-radius: 11px;
            padding: 1.25rem; text-align: center; cursor: pointer;
            transition: border-color .2s, background .2s; position: relative;
        }
        .doc-upload:hover { border-color: var(--laurel); background: rgba(169,179,136,.05); }
        .doc-upload input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; }
        .doc-upload-icon { font-size: 1.5rem; margin-bottom: .35rem; }
        .doc-upload-label { font-size: .78rem; color: var(--camel); line-height: 1.5; }
        .doc-upload-label strong { color: var(--olive); }
        .doc-preview-name {
            margin-top: .5rem; font-size: .75rem; font-weight: 600;
            color: var(--olive); display: none;
        }

        /* Map hint */
        .map-hint {
            background: rgba(169,179,136,.1); border: 1px solid rgba(169,179,136,.25);
            border-radius: 10px; padding: .75rem 1rem;
            font-size: .78rem; color: var(--olive); display: flex; align-items: flex-start; gap: .5rem;
        }

        /* Alert */
        .alert-error {
            background: rgba(161,58,42,.06); border: 1px solid rgba(161,58,42,.2);
            border-radius: 10px; padding: .75rem 1rem;
            font-size: .83rem; color: var(--error); margin-bottom: 1.25rem;
        }
        .alert-error ul { list-style: none; }
        .alert-error li + li { margin-top: .2rem; }

        /* Submit */
        .submit-area { padding: 1.25rem 2rem; border-top: 1px solid rgba(56,44,35,.06); display: flex; gap: .75rem; align-items: center; }
        .btn-submit {
            flex: 1; padding: .85rem;
            font-family: var(--font-b); font-size: .95rem; font-weight: 700;
            color: var(--white); background: var(--olive);
            border: none; border-radius: 12px; cursor: pointer;
            box-shadow: 0 4px 14px rgba(95,111,82,.3);
            transition: background .2s, transform .1s;
        }
        .btn-submit:hover { background: var(--charcoal); }
        .btn-submit:active { transform: scale(.98); }
        .login-link { font-size: .8rem; color: var(--camel); text-align: center; }
        .login-link a { color: var(--olive); font-weight: 600; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }

        @media (max-width: 560px) { .form-row { grid-template-columns: 1fr; } .card-body, .card-head { padding-left: 1.25rem; padding-right: 1.25rem; } }
    </style>
</head>
<body>

<div class="page-wrap">

    <div class="brand">
        <a href="/" class="brand-logo">
            <span class="brand-icon">🌿</span>
            <span class="brand-name">EcoEats</span>
        </a>
        <p class="brand-tagline">Daftar sebagai Mitra Merchant</p>
    </div>

    <div class="steps">
        <div class="step active"><div class="step-num">1</div> Akun</div>
        <div class="step-line"></div>
        <div class="step active"><div class="step-num">2</div> Profil Usaha</div>
        <div class="step-line"></div>
        <div class="step active"><div class="step-num">3</div> Dokumen</div>
    </div>

    @if ($errors->any())
    <div class="alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('merchant.register.post') }}" enctype="multipart/form-data">
        @csrf

        {{-- Bagian 1: Info Akun --}}
        <div class="card">
            <div class="card-head">
                <div class="section-badge">Langkah 1</div>
                <div class="card-title">Informasi Akun</div>
                <div class="card-sub">Data untuk login ke portal EcoEats Merchant.</div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <input class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               type="text" id="name" name="name"
                               value="{{ old('name') }}" autofocus placeholder="Nama pemilik usaha">
                        @error('name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">No. HP <span class="opt">(opsional)</span></label>
                        <input class="form-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               type="tel" id="phone" name="phone"
                               value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="email@usaha.com">
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               type="password" id="password" name="password"
                               placeholder="Minimal 8 karakter">
                        @error('password')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                        <input class="form-input" type="password"
                               id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi password">
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian 2: Profil Usaha --}}
        <div class="card">
            <div class="card-head">
                <div class="section-badge">Langkah 2</div>
                <div class="card-title">Profil Usaha</div>
                <div class="card-sub">Informasi tentang usaha kuliner Anda di Solo.</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" for="business_name">Nama Usaha</label>
                    <input class="form-input {{ $errors->has('business_name') ? 'is-invalid' : '' }}"
                           type="text" id="business_name" name="business_name"
                           value="{{ old('business_name') }}" placeholder="contoh: Warung Nasi Bu Sari">
                    @error('business_name')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="business_address">Alamat Lengkap</label>
                    <textarea class="form-textarea {{ $errors->has('business_address') ? 'is-invalid' : '' }}"
                              id="business_address" name="business_address"
                              placeholder="Jl. Slamet Riyadi No. 10, Laweyan, Kota Solo">{{ old('business_address') }}</textarea>
                    @error('business_address')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="map-hint">
                    <span></span>
                    <span>Untuk mendapatkan koordinat: buka Google Maps → cari lokasi usaha → klik kanan → salin angka latitude & longitude yang muncul.</span>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="latitude">Latitude</label>
                        <input class="form-input {{ $errors->has('latitude') ? 'is-invalid' : '' }}"
                               type="number" id="latitude" name="latitude" step="any"
                               value="{{ old('latitude') }}" placeholder="-7.5695">
                        @error('latitude')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="longitude">Longitude</label>
                        <input class="form-input {{ $errors->has('longitude') ? 'is-invalid' : '' }}"
                               type="number" id="longitude" name="longitude" step="any"
                               value="{{ old('longitude') }}" placeholder="110.8270">
                        @error('longitude')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian 3: Dokumen --}}
        <div class="card">
            <div class="card-head">
                <div class="section-badge">Langkah 3</div>
                <div class="card-title">Dokumen Legalitas</div>
                <div class="card-sub">Dokumen akan ditinjau admin sebelum akun dapat berjualan. Format: JPG, PNG, atau PDF (maks. 4MB).</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" for="business_license">
                        Surat Izin Usaha <span class="opt">(opsional, bisa diupload nanti)</span>
                    </label>
                    <label class="doc-upload" for="business_license">
                        <div class="doc-upload-icon"></div>
                        <div class="doc-upload-label"><strong>Klik untuk unggah</strong><br>SIUP, NIB, atau izin usaha lainnya</div>
                        <div class="doc-preview-name" id="license-name"></div>
                        <input type="file" id="business_license" name="business_license"
                               accept=".jpg,.jpeg,.png,.pdf"
                               onchange="showFileName(this, 'license-name')">
                    </label>
                    @error('business_license')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="halal_cert">
                        Sertifikat Halal <span class="opt">(opsional)</span>
                    </label>
                    <label class="doc-upload" for="halal_cert">
                        <div class="doc-upload-icon"></div>
                        <div class="doc-upload-label"><strong>Klik untuk unggah</strong><br>Sertifikat halal MUI (jika ada)</div>
                        <div class="doc-preview-name" id="halal-name"></div>
                        <input type="file" id="halal_cert" name="halal_cert"
                               accept=".jpg,.jpeg,.png,.pdf"
                               onchange="showFileName(this, 'halal-name')">
                    </label>
                    @error('halal_cert')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="submit-area">
                <button type="submit" class="btn-submit">Daftar & Ajukan Verifikasi</button>
            </div>
        </div>
    </form>

    <div class="login-link">
        Sudah punya akun merchant?
        <a href="{{ route('merchant.login') }}">Masuk di sini</a>
    </div>

</div>

<script>
function showFileName(input, targetId) {
    const el = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        el.textContent = '✓ ' + input.files[0].name;
        el.style.display = 'block';
    }
}
</script>
</body>
</html>