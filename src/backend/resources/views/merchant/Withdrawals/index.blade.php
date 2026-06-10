{{--
    resources/views/merchant/withdrawals/index.blade.php
    Data dari: Merchant\WithdrawalController@index
    Variabel: $profile, $withdrawals (Paginator), $balanceData (array)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penarikan Dana — EcoEats Merchant</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .pg-head { margin-bottom:1.75rem; }
        .pg-head h1 { font-family:var(--font-d); font-size:1.55rem; color:var(--charcoal); }
        .pg-crumb { font-size:.73rem; color:var(--camel); margin-top:.2rem; }

        .flash { padding:.75rem 1rem; border-radius:10px; font-size:.83rem; margin-bottom:1.25rem; }
        .flash-success { background:rgba(95,111,82,.1); border:1px solid rgba(95,111,82,.25); color:var(--olive); }
        .flash-error   { background:rgba(180,60,60,.08); border:1px solid rgba(180,60,60,.2); color:#a03030; }

        /* Balance cards */
        .balance-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:2rem; }
        .bc { background:var(--white); border-radius:14px; padding:1.25rem 1.5rem; box-shadow:var(--shadow); border-top:3px solid transparent; }
        .bc.main-balance { border-top-color:var(--olive); }
        .bc.revenue      { border-top-color:var(--laurel); }
        .bc.withdrawn    { border-top-color:var(--camel); }
        .bc-label { font-size:.68rem; font-weight:700; color:var(--camel); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.4rem; }
        .bc-val { font-family:var(--font-d); font-size:1.6rem; color:var(--charcoal); }
        .bc-note { font-size:.72rem; color:var(--laurel); margin-top:.3rem; }
        .bc.main-balance .bc-val { color:var(--olive); }

        /* Layout kanan-kiri */
        .content-grid { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }

        /* Panel */
        .panel { background:var(--white); border-radius:14px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.25rem; }
        .panel-head { padding:1rem 1.5rem; border-bottom:1px solid rgba(56,44,35,.06); display:flex; align-items:center; justify-content:space-between; }
        .panel-title { font-family:var(--font-d); font-size:1rem; color:var(--charcoal); }
        .panel-body { padding:1.25rem 1.5rem; }

        /* Table */
        table { width:100%; border-collapse:collapse; }
        thead tr { background:rgba(56,44,35,.025); }
        th { text-align:left; padding:.6rem 1.25rem; font-size:.67rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:rgba(56,44,35,.38); white-space:nowrap; }
        td { padding:.8rem 1.25rem; font-size:.82rem; color:var(--charcoal); border-top:1px solid rgba(56,44,35,.05); vertical-align:middle; }
        tr:hover td { background:rgba(56,44,35,.012); }

        /* Badge */
        .badge { display:inline-block; padding:.18rem .6rem; border-radius:999px; font-size:.67rem; font-weight:700; }
        .b-pending    { background:rgba(185,148,112,.12); color:#7a4e20; }
        .b-processing { background:rgba(95,111,82,.12);   color:var(--olive); }
        .b-completed  { background:rgba(95,111,82,.08);   color:var(--olive); }
        .b-rejected   { background:rgba(180,60,60,.08);   color:#a03030; }

        .amount-val { font-family:var(--font-d); color:var(--olive); font-size:.95rem; }

        /* Form */
        .form-group { margin-bottom:1rem; }
        .form-label { display:block; font-size:.76rem; font-weight:600; color:var(--olive); margin-bottom:.38rem; }
        .form-label .req { color:#a03030; }
        .form-input { width:100%; padding:.62rem .9rem; font-family:var(--font-b); font-size:.85rem; color:var(--charcoal); background:rgba(56,44,35,.03); border:1.5px solid rgba(56,44,35,.1); border-radius:9px; outline:none; transition:border-color .2s; }
        .form-input:focus { border-color:var(--laurel); background:var(--white); box-shadow:0 0 0 3px rgba(169,179,136,.15); }
        .form-input.is-invalid { border-color:#a03030; }
        .field-err { font-size:.73rem; color:#a03030; margin-top:.25rem; }
        .form-hint { font-size:.72rem; color:var(--camel); margin-top:.25rem; }

        .btn-submit { width:100%; padding:.7rem; background:var(--camel); color:white; border:none; border-radius:9px; font-family:var(--font-b); font-size:.88rem; font-weight:700; cursor:pointer; transition:background .2s; margin-top:.25rem; }
        .btn-submit:hover { background:var(--charcoal); }
        .btn-submit:disabled { background:rgba(185,148,112,.4); cursor:not-allowed; }

        /* Warning notice */
        .notice { background:rgba(185,148,112,.08); border:1.5px solid rgba(185,148,112,.25); border-radius:10px; padding:.85rem 1rem; font-size:.78rem; color:var(--camel); display:flex; gap:.5rem; margin-bottom:1rem; line-height:1.5; }

        .empty { text-align:center; padding:3rem; color:var(--camel); }
        .empty .ei { font-size:2rem; margin-bottom:.6rem; opacity:.4; }
        .empty p { font-size:.82rem; }

        .pager-row { display:flex; justify-content:center; margin-top:1.25rem; }

        @media(max-width:960px) { .balance-grid { grid-template-columns:1fr 1fr; } .content-grid { grid-template-columns:1fr; } .sidebar { display:none; } .main { margin-left:0; } }
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
        <a href="{{ route('merchant.orders.index') }}" class="nav-item">📋 Pesanan Masuk</a>
        <div class="nav-label">Keuangan</div>
        <a href="{{ route('merchant.withdrawals.index') }}" class="nav-item active">💰 Penarikan Dana</a>
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

    @if(session('success'))<div class="flash flash-success">✅ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="flash flash-error">⚠️ {{ session('error') }}</div>@endif

    <div class="pg-head">
        <h1>Penarikan Dana</h1>
        <div class="pg-crumb">EcoEats › Merchant › Penarikan Dana</div>
    </div>

    {{-- Saldo --}}
    <div class="balance-grid">
        <div class="bc main-balance">
            <div class="bc-label">Saldo Tersedia</div>
            <div class="bc-val">Rp{{ number_format($balanceData['balance'], 0, ',', '.') }}</div>
            <div class="bc-note">dapat ditarik sekarang</div>
        </div>
        <div class="bc revenue">
            <div class="bc-label">Total Pendapatan</div>
            <div class="bc-val">Rp{{ number_format($balanceData['total_revenue'], 0, ',', '.') }}</div>
            <div class="bc-note">dari semua pesanan selesai</div>
        </div>
        <div class="bc withdrawn">
            <div class="bc-label">Sudah Ditarik</div>
            <div class="bc-val">Rp{{ number_format($balanceData['total_withdrawn'], 0, ',', '.') }}</div>
            <div class="bc-note">selesai + sedang diproses</div>
        </div>
    </div>

    <div class="content-grid">

        {{-- Riwayat withdrawal --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-title">Riwayat Penarikan</div>
                <span style="font-size:.75rem; color:var(--camel)">{{ $withdrawals->total() }} total</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nominal</th>
                        <th>Bank</th>
                        <th>Tanggal Ajuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                    <tr>
                        <td style="color:var(--camel); font-size:.75rem">{{ $w->id }}</td>
                        <td><span class="amount-val">Rp{{ number_format($w->amount, 0, ',', '.') }}</span></td>
                        <td>
                            <div style="font-weight:600">{{ $w->bank_name }}</div>
                            <div style="font-size:.73rem; color:var(--camel)">{{ $w->bank_account_number }}</div>
                        </td>
                        <td style="font-size:.75rem; color:var(--camel)">
                            {{ $w->requested_at->format('d M Y') }}
                        </td>
                        <td>
                            <span class="badge {{ $w->statusBadgeClass() }}">{{ $w->statusLabel() }}</span>
                            @if($w->isCompleted() && $w->transfer_proof_url)
                                <br>
                                <a href="{{ asset('storage/'.$w->transfer_proof_url) }}"
                                   target="_blank"
                                   style="font-size:.7rem; color:var(--olive); font-weight:600; text-decoration:none;">
                                    Lihat bukti ↗
                                </a>
                            @endif
                            @if($w->admin_notes)
                                <div style="font-size:.71rem; color:var(--camel); margin-top:.2rem">
                                    {{ Str::limit($w->admin_notes, 60) }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty">
                                <div class="ei">💰</div>
                                <p>Belum ada riwayat penarikan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($withdrawals->hasPages())
            <div class="pager-row" style="padding:1rem">{{ $withdrawals->links() }}</div>
            @endif
        </div>

        {{-- Form ajukan withdrawal --}}
        <div>
            <div class="panel">
                <div class="panel-head"><div class="panel-title">Ajukan Penarikan</div></div>
                <div class="panel-body">

                    @php
                        $hasPending = $withdrawals->contains(fn($w) =>
                            in_array($w->status, ['pending','processing'])
                        );
                    @endphp

                    @if($hasPending)
                    <div class="notice">
                        <span>⏳</span>
                        <span>Anda masih memiliki penarikan yang sedang diproses. Tunggu hingga selesai sebelum mengajukan yang baru.</span>
                    </div>
                    @elseif($balanceData['balance'] <= 0)
                    <div class="notice">
                        <span>ℹ️</span>
                        <span>Saldo Anda saat ini Rp 0. Selesaikan lebih banyak pesanan untuk menambah saldo.</span>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('merchant.withdrawals.store') }}">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Nominal <span class="req">*</span></label>
                            <input class="form-input {{ $errors->has('amount') ? 'is-invalid' : '' }}"
                                   type="number" name="amount"
                                   value="{{ old('amount') }}"
                                   min="10000"
                                   max="{{ $balanceData['balance'] }}"
                                   placeholder="Minimal Rp 10.000"
                                   {{ $hasPending || $balanceData['balance'] <= 0 ? 'disabled' : '' }}>
                            <div class="form-hint">
                                Saldo tersedia: <strong>Rp{{ number_format($balanceData['balance'], 0, ',', '.') }}</strong>
                            </div>
                            @error('amount')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Bank <span class="req">*</span></label>
                            <input class="form-input {{ $errors->has('bank_name') ? 'is-invalid' : '' }}"
                                   type="text" name="bank_name"
                                   value="{{ old('bank_name') }}"
                                   placeholder="BCA, BRI, Mandiri, dll."
                                   {{ $hasPending || $balanceData['balance'] <= 0 ? 'disabled' : '' }}>
                            @error('bank_name')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor Rekening <span class="req">*</span></label>
                            <input class="form-input {{ $errors->has('bank_account_number') ? 'is-invalid' : '' }}"
                                   type="text" name="bank_account_number"
                                   value="{{ old('bank_account_number') }}"
                                   placeholder="Nomor rekening tujuan"
                                   {{ $hasPending || $balanceData['balance'] <= 0 ? 'disabled' : '' }}>
                            @error('bank_account_number')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Pemilik Rekening <span class="req">*</span></label>
                            <input class="form-input {{ $errors->has('bank_account_name') ? 'is-invalid' : '' }}"
                                   type="text" name="bank_account_name"
                                   value="{{ old('bank_account_name') }}"
                                   placeholder="Nama sesuai buku tabungan"
                                   {{ $hasPending || $balanceData['balance'] <= 0 ? 'disabled' : '' }}>
                            @error('bank_account_name')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn-submit"
                                {{ $hasPending || $balanceData['balance'] <= 0 ? 'disabled' : '' }}>
                            Ajukan Penarikan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</main>
</body>
</html>