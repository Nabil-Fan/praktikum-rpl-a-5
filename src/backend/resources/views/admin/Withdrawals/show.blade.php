{{--
    resources/views/admin/withdrawals/show.blade.php
    Data dari: Admin\WithdrawalController@show
    Variabel: $withdrawal (Withdrawal, with merchant.user, admin)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penarikan #{{ $withdrawal->id }} — EcoEats Admin</title>
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
        .pg-head { margin-bottom:1.75rem; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.45rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        .flash { padding:.75rem 1rem; border-radius:10px; font-size:.83rem; margin-bottom:1.25rem; }
        .flash-success { background:rgba(95,111,82,.1); border:1px solid rgba(95,111,82,.25); color:var(--olive); }
        .flash-error   { background:rgba(180,60,60,.08); border:1px solid rgba(180,60,60,.2); color:#a03030; }

        .badge { display:inline-block; padding:.22rem .7rem; border-radius:999px; font-size:.73rem; font-weight:700; }
        .b-pending    { background:rgba(185,148,112,.12); color:#7a4e20; }
        .b-processing { background:rgba(95,111,82,.12);   color:var(--olive); }
        .b-completed  { background:rgba(95,111,82,.08);   color:var(--olive); }
        .b-rejected   { background:rgba(180,60,60,.08);   color:#a03030; }

        .grid { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }

        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.25rem; }
        .panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-body { padding:1.25rem 1.5rem; }

        .info-row { display:flex; gap:.75rem; padding:.6rem 0; border-bottom:1px solid rgba(56,44,35,.05); align-items:flex-start; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:.73rem; font-weight:600; color:var(--camel); min-width:140px; flex-shrink:0; padding-top:.05rem; }
        .info-val { font-size:.83rem; color:var(--charcoal); line-height:1.5; }

        /* Amount highlight */
        .amount-box { background:rgba(95,111,82,.06); border:1.5px solid rgba(95,111,82,.15); border-radius:12px; padding:1rem 1.25rem; text-align:center; margin-bottom:1.25rem; }
        .amount-label { font-size:.68rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--camel); margin-bottom:.35rem; }
        .amount-val { font-family:var(--font-d); font-size:2rem; color:var(--olive); }

        /* Action panel */
        .action-section { padding:1.1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); }
        .action-section:last-child { border-bottom:none; }
        .action-title { font-size:.83rem; font-weight:700; color:var(--charcoal); margin-bottom:.75rem; }

        .form-label { display:block; font-size:.75rem; font-weight:600; color:var(--olive); margin-bottom:.38rem; }
        .form-label .req { color:#a03030; }
        .form-textarea { width:100%; padding:.62rem .9rem; font-family:var(--font-b); font-size:.83rem; color:var(--charcoal); background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1); border-radius:9px; outline:none; resize:vertical; min-height:70px; transition:border-color .2s; }
        .form-textarea:focus { border-color:var(--laurel); }

        /* File upload */
        .file-upload { border:2px dashed rgba(56,44,35,.13); border-radius:10px; padding:1rem; text-align:center; cursor:pointer; transition:all .2s; position:relative; }
        .file-upload:hover { border-color:var(--laurel); background:rgba(169,179,136,.05); }
        .file-upload input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; }
        .file-upload-text { font-size:.78rem; color:var(--camel); }
        .file-upload-text strong { color:var(--olive); }
        .file-name { font-size:.74rem; color:var(--olive); font-weight:600; margin-top:.4rem; display:none; }

        .btn { width:100%; padding:.65rem; border-radius:9px; font-family:var(--font-b); font-size:.85rem; font-weight:700; cursor:pointer; border:none; transition:all .15s; margin-top:.65rem; }
        .btn-approve  { background:var(--olive); color:white; }
        .btn-approve:hover { background:var(--charcoal); }
        .btn-complete { background:var(--camel); color:white; }
        .btn-complete:hover { background:var(--charcoal); }
        .btn-reject   { background:transparent; color:#a03030; border:1.5px solid rgba(180,60,60,.25); }
        .btn-reject:hover { background:rgba(180,60,60,.06); }

        /* Proof image */
        .proof-img { width:100%; border-radius:9px; margin-top:.75rem; }

        /* Done notice */
        .done-notice { padding:1.25rem 1.5rem; text-align:center; }
        .done-icon { font-size:2rem; margin-bottom:.5rem; }
        .done-text { font-size:.83rem; color:var(--camel); }

        @media(max-width:860px) { .grid { grid-template-columns:1fr; } .sidenav { display:none; } }
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
        <a href="{{ route('admin.withdrawals.index') }}" class="sn-item active">💰 Penarikan Dana</a>
        <div class="sn-label">Sistem</div>
        <a href="{{ route('admin.categories.index') }}" class="sn-item">🏷 Kategori</a>
        <a href="{{ route('admin.accounts.create') }}" class="sn-item">➕ Tambah Akun</a>
        <a href="{{ route('admin.map') }}" class="sn-item">🗺 Peta Merchant</a>
    </aside>

    <main class="content">
        <a href="{{ route('admin.withdrawals.index') }}" class="back-link">← Kembali ke Daftar</a>

        @if(session('success'))<div class="flash flash-success">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="flash flash-error">⚠️ {{ session('error') }}</div>@endif

        <div class="pg-head">
            <div>
                <h1>Penarikan Dana #{{ $withdrawal->id }}</h1>
                <div class="pg-crumb">EcoEats › Admin › Penarikan Dana › Detail</div>
            </div>
            <span class="badge b-{{ $withdrawal->status }}">{{ $withdrawal->statusLabel() }}</span>
        </div>

        <div class="grid">

            {{-- Kiri: info detail --}}
            <div>
                {{-- Nominal --}}
                <div class="amount-box">
                    <div class="amount-label">Nominal Penarikan</div>
                    <div class="amount-val">Rp{{ number_format($withdrawal->amount, 0, ',', '.') }}</div>
                </div>

                {{-- Info merchant --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Informasi Merchant</div></div>
                    <div class="panel-body">
                        <div class="info-row">
                            <div class="info-label">Nama Usaha</div>
                            <div class="info-val"><strong>{{ $withdrawal->merchant->business_name ?? '—' }}</strong></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pemilik</div>
                            <div class="info-val">{{ $withdrawal->merchant->user->name ?? '—' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-val">{{ $withdrawal->merchant->user->email ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Info rekening --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Tujuan Transfer</div></div>
                    <div class="panel-body">
                        <div class="info-row">
                            <div class="info-label">Nama Bank</div>
                            <div class="info-val"><strong>{{ $withdrawal->bank_name }}</strong></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Nomor Rekening</div>
                            <div class="info-val" style="font-family:monospace; font-size:.9rem">{{ $withdrawal->bank_account_number }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Nama Pemilik</div>
                            <div class="info-val">{{ $withdrawal->bank_account_name }}</div>
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Riwayat Status</div></div>
                    <div class="panel-body">
                        @php
                            $steps = [
                                ['label' => 'Diajukan',    'time' => $withdrawal->requested_at],
                                ['label' => 'Diproses',    'time' => $withdrawal->processed_at],
                                ['label' => 'Selesai',     'time' => $withdrawal->completed_at],
                            ];
                        @endphp
                        @foreach($steps as $step)
                        <div style="display:flex; gap:.75rem; padding:.5rem 0; align-items:flex-start;">
                            <div style="width:10px; height:10px; border-radius:50%; flex-shrink:0; margin-top:.3rem; background:{{ $step['time'] ? 'var(--olive)' : 'rgba(56,44,35,.15)' }}"></div>
                            <div>
                                <div style="font-size:.78rem; font-weight:600; color:{{ $step['time'] ? 'var(--charcoal)' : 'rgba(56,44,35,.35)' }}">{{ $step['label'] }}</div>
                                @if($step['time'])
                                    <div style="font-size:.7rem; color:var(--camel)">{{ $step['time']->format('d M Y, H:i') }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @if($withdrawal->isRejected())
                        <div style="display:flex; gap:.75rem; padding:.5rem 0; align-items:flex-start;">
                            <div style="width:10px; height:10px; border-radius:50%; flex-shrink:0; margin-top:.3rem; background:#a03030"></div>
                            <div>
                                <div style="font-size:.78rem; font-weight:600; color:#a03030">Ditolak</div>
                            </div>
                        </div>
                        @endif
                        @if($withdrawal->admin_notes)
                        <div style="margin-top:.75rem; padding:.65rem .85rem; background:rgba(56,44,35,.04); border-radius:8px; font-size:.78rem; color:var(--camel); line-height:1.5;">
                            <strong style="color:var(--charcoal)">Catatan Admin:</strong> {{ $withdrawal->admin_notes }}
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Bukti transfer jika sudah selesai --}}
                @if($withdrawal->transfer_proof_url)
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Bukti Transfer</div></div>
                    <div class="panel-body">
                        <img src="{{ asset('storage/'.$withdrawal->transfer_proof_url) }}"
                             alt="Bukti Transfer" class="proof-img">
                        <div style="margin-top:.75rem; text-align:center;">
                            <a href="{{ asset('storage/'.$withdrawal->transfer_proof_url) }}"
                               target="_blank"
                               style="font-size:.78rem; font-weight:600; color:var(--olive); text-decoration:none;">
                                Buka gambar penuh ↗
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Kanan: aksi admin --}}
            <div>
                <div class="panel">

                    @if($withdrawal->isPending())
                    {{-- Approve --}}
                    <div class="action-section">
                        <div class="action-title">✅ Setujui & Proses</div>
                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}">
                            @csrf
                            <label class="form-label">Catatan <span style="font-weight:400;color:var(--camel)">(opsional)</span></label>
                            <textarea class="form-textarea" name="admin_notes" placeholder="Misal: Sedang diproses tim keuangan."></textarea>
                            <button type="submit" class="btn btn-approve">Setujui & Mulai Proses</button>
                        </form>
                    </div>
                    {{-- Reject --}}
                    <div class="action-section">
                        <div class="action-title">❌ Tolak</div>
                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                            @csrf
                            <label class="form-label">Alasan penolakan <span class="req">*</span></label>
                            <textarea class="form-textarea" name="admin_notes" required placeholder="Tulis alasan penolakan…"></textarea>
                            @error('admin_notes')<div style="font-size:.73rem;color:#a03030;margin-top:.25rem">{{ $message }}</div>@enderror
                            <button type="submit" class="btn btn-reject">Tolak Penarikan</button>
                        </form>
                    </div>

                    @elseif($withdrawal->isProcessing())
                    {{-- Complete + upload bukti --}}
                    <div class="action-section">
                        <div class="action-title">✔ Selesaikan & Upload Bukti</div>
                        <form method="POST" action="{{ route('admin.withdrawals.complete', $withdrawal) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <label class="form-label">Bukti Transfer <span class="req">*</span></label>
                            <label class="file-upload" for="proof-input">
                                <div class="file-upload-text">
                                    <strong>Klik untuk upload</strong><br>JPG, PNG, atau PDF (maks. 4MB)
                                </div>
                                <div class="file-name" id="proof-name"></div>
                                <input type="file" id="proof-input" name="transfer_proof"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       onchange="showName(this)">
                            </label>
                            @error('transfer_proof')<div style="font-size:.73rem;color:#a03030;margin-top:.25rem">{{ $message }}</div>@enderror
                            <label class="form-label" style="margin-top:.75rem">Catatan <span style="font-weight:400;color:var(--camel)">(opsional)</span></label>
                            <textarea class="form-textarea" name="admin_notes" placeholder="Misal: Transfer berhasil via BCA.">{{ $withdrawal->admin_notes }}</textarea>
                            <button type="submit" class="btn btn-complete">Selesaikan & Upload Bukti</button>
                        </form>
                    </div>
                    {{-- Masih bisa reject saat processing --}}
                    <div class="action-section">
                        <div class="action-title">❌ Batalkan & Tolak</div>
                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                            @csrf
                            <label class="form-label">Alasan <span class="req">*</span></label>
                            <textarea class="form-textarea" name="admin_notes" required placeholder="Tulis alasan pembatalan…"></textarea>
                            <button type="submit" class="btn btn-reject">Tolak Penarikan</button>
                        </form>
                    </div>

                    @else
                    <div class="done-notice">
                        <div class="done-icon">{{ $withdrawal->isCompleted() ? '✅' : '❌' }}</div>
                        <div style="font-family:var(--font-d); font-size:.95rem; margin-bottom:.3rem">
                            {{ $withdrawal->isCompleted() ? 'Penarikan Selesai' : 'Penarikan Ditolak' }}
                        </div>
                        <div class="done-text">Tidak ada tindakan lebih lanjut yang diperlukan.</div>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </main>
</div>

<script>
function showName(input) {
    const el = document.getElementById('proof-name');
    if (input.files && input.files[0]) {
        el.textContent = '✓ ' + input.files[0].name;
        el.style.display = 'block';
    }
}
</script>
</body>
</html>