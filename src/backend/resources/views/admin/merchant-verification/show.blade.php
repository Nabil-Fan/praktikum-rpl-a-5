{{--
    resources/views/admin/merchant-verification/show.blade.php
    Data dari: App\Http\Controllers\Admin\MerchantVerificationController@show
    Variabel: $merchant (MerchantProfile, with user, verifications.admin)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Merchant — EcoEats Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --olive:#5F6F52; --laurel:#A9B388; --cornsilk:#FEFAE0;
            --camel:#B99470; --charcoal:#382C23; --bg:#eee9df; --white:#fff;
            --font-d:'Fraunces',serif; --font-b:'Plus Jakarta Sans',sans-serif;
            --shadow:0 1px 3px rgba(56,44,35,.06),0 4px 16px rgba(56,44,35,.07);
        }
        body { font-family:var(--font-b); background:var(--bg); color:var(--charcoal); min-height:100vh; }

        header { background:var(--charcoal); height:58px; display:flex; align-items:center; padding:0 1.75rem; gap:1.25rem; position:sticky; top:0; z-index:100; }
        .h-brand { font-family:var(--font-d); font-size:1.15rem; color:var(--cornsilk); }
        .h-sep { width:1px; height:18px; background:rgba(254,250,224,.12); }
        .h-label { font-size:.68rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--camel); }
        .h-right { margin-left:auto; display:flex; align-items:center; gap:1rem; }
        .h-user { font-size:.8rem; color:rgba(254,250,224,.65); }
        .h-user strong { color:var(--cornsilk); }
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

        .pg-head { margin-bottom:1.75rem; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        .badge { display:inline-block; padding:.22rem .7rem; border-radius:999px; font-size:.72rem; font-weight:700; }
        .b-pending  { background:rgba(185,148,112,.12); color:#7a4e20; }
        .b-approved { background:rgba(95,111,82,.12); color:var(--olive); }
        .b-rejected { background:rgba(180,60,60,.08); color:#a03030; }

        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem; }
        .grid-3 { display:grid; grid-template-columns:2fr 1fr; gap:1.5rem; }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.5rem; }
        .panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-body { padding:1.25rem 1.5rem; }

        /* Info rows */
        .info-row { display:flex; gap:1rem; padding:.6rem 0; border-bottom:1px solid rgba(56,44,35,.05); align-items:flex-start; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:.73rem; font-weight:600; color:var(--camel); min-width:120px; flex-shrink:0; padding-top:.05rem; }
        .info-val { font-size:.83rem; color:var(--charcoal); line-height:1.5; }

        /* Document preview */
        .doc-card { border:1.5px solid rgba(56,44,35,.1); border-radius:10px; overflow:hidden; margin-top:.5rem; }
        .doc-preview { height:140px; background:rgba(56,44,35,.04); display:flex; align-items:center; justify-content:center; }
        .doc-preview img { width:100%; height:100%; object-fit:cover; }
        .doc-preview .doc-placeholder { font-size:2rem; opacity:.3; }
        .doc-footer { padding:.6rem .85rem; display:flex; align-items:center; justify-content:space-between; border-top:1px solid rgba(56,44,35,.07); }
        .doc-name { font-size:.73rem; font-weight:600; color:var(--charcoal); }
        .doc-link { font-size:.73rem; color:var(--olive); text-decoration:none; font-weight:600; }
        .doc-link:hover { text-decoration:underline; }
        .doc-missing { font-size:.78rem; color:rgba(56,44,35,.35); font-style:italic; padding:.5rem 0; }

        /* Action forms */
        .action-panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; }
        .action-section { padding:1.25rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .action-section:last-child { border-bottom:none; }
        .action-title { font-size:.83rem; font-weight:700; color:var(--charcoal); margin-bottom:.75rem; }
        .form-label { display:block; font-size:.75rem; font-weight:600; color:var(--olive); margin-bottom:.4rem; }
        .form-textarea {
            width:100%; padding:.65rem .9rem; font-family:var(--font-b); font-size:.83rem;
            color:var(--charcoal); background:rgba(56,44,35,.03);
            border:1.5px solid rgba(56,44,35,.1); border-radius:9px;
            outline:none; resize:vertical; min-height:80px;
            transition:border-color .2s;
        }
        .form-textarea:focus { border-color:var(--laurel); background:white; }
        .btn-approve { width:100%; padding:.65rem; margin-top:.75rem; background:var(--olive); color:white; border:none; border-radius:9px; font-family:var(--font-b); font-size:.83rem; font-weight:700; cursor:pointer; transition:background .2s; }
        .btn-approve:hover { background:var(--charcoal); }
        .btn-reject  { width:100%; padding:.65rem; margin-top:.75rem; background:transparent; color:#a03030; border:1.5px solid rgba(180,60,60,.3); border-radius:9px; font-family:var(--font-b); font-size:.83rem; font-weight:700; cursor:pointer; transition:all .2s; }
        .btn-reject:hover { background:rgba(180,60,60,.07); }

        /* Already decided notice */
        .decided-notice { padding:1.25rem 1.5rem; text-align:center; }
        .decided-notice .di { font-size:2rem; margin-bottom:.5rem; }
        .decided-notice p { font-size:.83rem; color:var(--camel); }
        .decided-notice .dt { font-size:.95rem; font-weight:700; color:var(--charcoal); margin-bottom:.3rem; }

        /* Verification history */
        .vh-item { padding:.75rem 0; border-bottom:1px solid rgba(56,44,35,.05); }
        .vh-item:last-child { border-bottom:none; }
        .vh-top { display:flex; align-items:center; gap:.6rem; margin-bottom:.3rem; }
        .vh-action { font-size:.72rem; font-weight:700; }
        .va-approved { color:var(--olive); }
        .va-rejected { color:#a03030; }
        .vh-by { font-size:.72rem; color:var(--camel); }
        .vh-date { font-size:.72rem; color:var(--camel); margin-left:auto; }
        .vh-notes { font-size:.78rem; color:rgba(56,44,35,.6); line-height:1.5; }

        @media(max-width:860px) { .grid-2,.grid-3 { grid-template-columns:1fr; } .sidenav { display:none; } }
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
        <a href="{{ route('admin.merchants.index') }}" class="sn-item active">🏪 Verifikasi Merchant</a>
        <a href="{{ route('admin.users.index') }}" class="sn-item">👥 Pengguna</a>
        <a href="{{ route('admin.food-listings.index') }}" class="sn-item">🍱 Food Listing</a>
    </aside>

    <main class="content">
        <a href="{{ route('admin.merchants.index') }}" class="back-link">← Kembali ke Daftar</a>

        <div class="pg-head">
            <div>
                <h1>{{ $merchant->business_name }}</h1>
                <div class="pg-crumb">EcoEats › Admin › Verifikasi Merchant › Detail</div>
            </div>
            <span class="badge b-{{ $merchant->verification_status }}">
                {{ match($merchant->verification_status) {
                    'pending'  => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                } }}
            </span>
        </div>

        <div class="grid-3">
            {{-- Left: info + docs --}}
            <div>
                {{-- Info usaha --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Informasi Usaha</div></div>
                    <div class="panel-body">
                        <div class="info-row">
                            <div class="info-label">Nama Usaha</div>
                            <div class="info-val"><strong>{{ $merchant->business_name }}</strong></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pemilik</div>
                            <div class="info-val">{{ $merchant->user->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-val">{{ $merchant->user->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">No. HP</div>
                            <div class="info-val">{{ $merchant->user->phone ?? '—' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Alamat</div>
                            <div class="info-val">{{ $merchant->business_address }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Koordinat</div>
                            <div class="info-val">{{ $merchant->latitude }}, {{ $merchant->longitude }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Terdaftar</div>
                            <div class="info-val">{{ $merchant->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        @if($merchant->verified_at)
                        <div class="info-row">
                            <div class="info-label">Diverifikasi</div>
                            <div class="info-val">{{ $merchant->verified_at->format('d M Y, H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Dokumen --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Dokumen Unggahan</div></div>
                    <div class="panel-body">
                        <div style="margin-bottom:.5rem; font-size:.78rem; font-weight:600; color:var(--camel)">Surat Izin Usaha</div>
                        @if($merchant->business_license_url)
                        <div class="doc-card">
                            <div class="doc-preview">
                                <img src="{{ asset('storage/' . $merchant->business_license_url) }}"
                                     alt="Surat Izin Usaha"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                <div class="doc-placeholder" style="display:none">📄</div>
                            </div>
                            <div class="doc-footer">
                                <span class="doc-name">business_license</span>
                                <a href="{{ asset('storage/' . $merchant->business_license_url) }}"
                                   target="_blank" class="doc-link">Buka ↗</a>
                            </div>
                        </div>
                        @else
                        <div class="doc-missing">Tidak ada dokumen diunggah</div>
                        @endif

                        <div style="margin-top:1rem; margin-bottom:.5rem; font-size:.78rem; font-weight:600; color:var(--camel)">Sertifikat Halal</div>
                        @if($merchant->halal_cert_url)
                        <div class="doc-card">
                            <div class="doc-preview">
                                <img src="{{ asset('storage/' . $merchant->halal_cert_url) }}"
                                     alt="Sertifikat Halal"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                <div class="doc-placeholder" style="display:none">📄</div>
                            </div>
                            <div class="doc-footer">
                                <span class="doc-name">halal_certificate</span>
                                <a href="{{ asset('storage/' . $merchant->halal_cert_url) }}"
                                   target="_blank" class="doc-link">Buka ↗</a>
                            </div>
                        </div>
                        @else
                        <div class="doc-missing">Tidak diunggah (opsional)</div>
                        @endif
                    </div>
                </div>

                {{-- Riwayat verifikasi --}}
                @if($merchant->verifications->isNotEmpty())
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Riwayat Keputusan</div></div>
                    <div class="panel-body">
                        @foreach($merchant->verifications->sortByDesc('actioned_at') as $v)
                        <div class="vh-item">
                            <div class="vh-top">
                                <span class="vh-action va-{{ $v->action }}">
                                    {{ $v->action === 'approved' ? '✅ Disetujui' : '❌ Ditolak' }}
                                </span>
                                <span class="vh-by">oleh {{ $v->admin->name }}</span>
                                <span class="vh-date">{{ $v->actioned_at->format('d M Y H:i') }}</span>
                            </div>
                            @if($v->notes)
                            <div class="vh-notes">{{ $v->notes }}</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Right: action --}}
            <div>
                <div class="action-panel">
                    @if($merchant->verification_status === 'pending')
                    {{-- Approve --}}
                    <div class="action-section">
                        <div class="action-title">✅ Setujui Merchant</div>
                        <form method="POST" action="{{ route('admin.merchants.approve', $merchant) }}">
                            @csrf
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea class="form-textarea" name="notes" rows="3"
                                      placeholder="Misal: Dokumen lengkap dan valid."></textarea>
                            <button type="submit" class="btn-approve">Setujui Merchant</button>
                        </form>
                    </div>
                    {{-- Reject --}}
                    <div class="action-section">
                        <div class="action-title">❌ Tolak Merchant</div>
                        <form method="POST" action="{{ route('admin.merchants.reject', $merchant) }}">
                            @csrf
                            <label class="form-label">Alasan penolakan <span style="color:#a03030">*</span></label>
                            <textarea class="form-textarea" name="notes" rows="3" required
                                      placeholder="Misal: Dokumen izin usaha tidak terbaca."></textarea>
                            @error('notes')<div style="font-size:.75rem; color:#a03030; margin-top:.3rem">{{ $message }}</div>@enderror
                            <button type="submit" class="btn-reject">Tolak Merchant</button>
                        </form>
                    </div>
                    @else
                    <div class="decided-notice">
                        <div class="di">{{ $merchant->verification_status === 'approved' ? '✅' : '❌' }}</div>
                        <div class="dt">
                            {{ $merchant->verification_status === 'approved' ? 'Sudah Disetujui' : 'Sudah Ditolak' }}
                        </div>
                        <p>Keputusan telah diambil pada<br>{{ $merchant->verified_at?->format('d M Y') ?? '—' }}</p>
                        {{-- Tombol reset verifikasi --}}
                        <form method="POST" action="{{ route('admin.merchants.reset', $merchant) }}"
                              style="margin-top:1rem"
                              onsubmit="return confirm('Reset verifikasi merchant ini ke pending? Merchant harus ditinjau ulang oleh admin.')">
                            @csrf
                            <button type="submit"
                                    style="width:100%;padding:.6rem;background:transparent;color:var(--camel);border:1.5px solid rgba(185,148,112,.35);border-radius:8px;font-family:var(--font-b);font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s"
                                    onmouseover="this.style.background='rgba(185,148,112,.1)'" onmouseout="this.style.background='transparent'">
                                🔄 Reset ke Pending
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>