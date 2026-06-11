<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoEats — @yield('title', 'Masuk ke Portal')</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    /* ============================================================
       base.css — Reset, CSS variables, typography, animations
       ============================================================ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:      #FEFAE0;
      --card:    #ffffff;
      --lbl:     #503321;
      --sub:     #8A7560;
      --inp:     #F5F0CE;
      --brd:     #DDD8B0;
      --btn:     #B99470;
      --bth:     #9A7A58;
      --grn:     #5F6F52;
      --grnh:    #4A5840;
      --blu:     #6A7F8A;
      --red:     #B84040;
      --org:     #B87820;
      --inf:     #F8F5DC;
      --ibd:     #E4DDB0;
      --shadow:  0 2px 4px rgba(80,51,33,.04), 0 8px 24px rgba(80,51,33,.09);
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--lbl);
      font-size: 14px;
      line-height: 1.5;
    }

    h1, h2, h3, h4, .playfair { font-family: 'Playfair Display', serif; }
    .mono  { font-family: 'DM Mono', monospace; }
    .hide  { display: none !important; }

    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-thumb { background: var(--brd); border-radius: 3px; }
    ::-webkit-scrollbar-track { background: transparent; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes bounce {
      0%, 100% { transform: scale(1); }
      50%       { transform: scale(.985); }
    }
    .fade-in { animation: fadeUp .3s ease both; }

    /* ============================================================
       auth.css — Login / auth page styles
       ============================================================ */
    #page-auth {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 32px 16px;
      background: url('/images/Bg login.jpg') center center / cover no-repeat;
      background-attachment: fixed;
      position: relative;
      overflow: hidden;
      gap: 20px;
    }

    #page-auth::before {
      content: '';
      position: absolute;
      inset: 0;
      pointer-events: none;
      background: linear-gradient(
        160deg,
        rgba(40,22,10,.62)  0%,
        rgba(60,42,20,.42)  55%,
        rgba(40,22,10,.70)  100%
      );
    }

    .logo-area {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      z-index: 1;
    }
    .logo-word {
      font-family: 'Playfair Display', serif;
      font-size: 36px;
      color: #ffffff;
      line-height: 1;
      text-shadow: 0 2px 12px rgba(0,0,0,.35);
    }
    .logo-tag {
      font-size: 11px;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: rgba(255,255,255,.80);
      font-weight: 500;
    }

    .auth-card {
      width: 100%;
      max-width: 440px;
      background: rgba(254,250,224,.94);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border-radius: 20px;
      padding: 32px 36px;
      box-shadow: 0 8px 40px rgba(0,0,0,.35), 0 2px 8px rgba(0,0,0,.18);
      z-index: 1;
      transition: transform .15s ease;
      border: 1px solid rgba(255,255,255,.45);
    }

    .role-pill {
      display: inline-block;
      padding: 5px 14px;
      border-radius: 20px;
      border: 1.5px solid var(--brd);
      background: var(--inp);
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--sub);
      margin-bottom: 14px;
    }
    .role-pill.admin {
      border-color: #C8BBA0;
      background: #F5EEE0;
      color: #7A6040;
    }

    .auth-card h1   { font-size: 28px; color: var(--lbl); margin-bottom: 6px; }
    .auth-card p.subtitle { font-size: 14px; color: var(--sub); margin-bottom: 22px; }

    /* Form fields */
    .auth-form label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: var(--sub);
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: 5px;
    }
    .auth-form input[type=email],
    .auth-form input[type=password] {
      width: 100%;
      padding: 11px 14px;
      background: #FEFAE0;
      border: 1.5px solid transparent;
      border-radius: 8px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      color: var(--lbl);
      outline: none;
      transition: all .2s;
      margin-bottom: 14px;
    }
    .auth-form input:-webkit-autofill,
    .auth-form input:-webkit-autofill:hover,
    .auth-form input:-webkit-autofill:focus,
    .auth-form input:-webkit-autofill:active {
      -webkit-box-shadow: 0 0 0 999px #FEFAE0 inset !important;
      box-shadow: 0 0 0 999px #FEFAE0 inset !important;
      -webkit-text-fill-color: #503321 !important;
      caret-color: #503321;
    }
    .auth-form input:focus {
      border-color: var(--btn);
      box-shadow: 0 0 0 3px rgba(185,148,112,.18);
    }
    /* Error state */
    .auth-form input.is-invalid {
      border-color: var(--red);
      box-shadow: 0 0 0 3px rgba(184,64,64,.15);
    }
    .field-error {
      font-size: 12px;
      color: var(--red);
      margin-top: -10px;
      margin-bottom: 10px;
    }

    .auth-form .check-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 18px;
      font-size: 13px;
      color: var(--sub);
    }
    .auth-form .check-wrap input {
      width: 14px;
      height: 14px;
      accent-color: var(--btn);
      margin-bottom: 0;
    }

    .auth-btn {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14.5px;
      font-weight: 700;
      color: #FEFAE0;
      cursor: pointer;
      background: #503321;
      transition: all .2s;
    }
    .auth-btn:hover  { background: #3B1F0E; }
    .auth-btn:active { transform: scale(.984); }
    .auth-btn.admin  { background: #4A5840; }
    .auth-btn.admin:hover { background: #3a4632; }

    .info-box {
      background: var(--inf);
      border: 1px solid var(--ibd);
      border-radius: 12px;
      padding: 14px 16px;
      margin-top: 18px;
      font-size: 13px;
      color: var(--sub);
    }
    .info-box.admin-info { background: #F5EEE0; border-color: #C8BBA0; }
    .info-box strong { display: block; color: var(--lbl); margin-bottom: 4px; font-size: 13px; }

    /* Role switcher */
    .role-switch {
      z-index: 1;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 12px;
      color: rgba(255,255,255,.85);
    }
    .pills-wrap {
      display: inline-flex;
      background: rgba(255,255,255,.18);
      padding: 5px;
      border-radius: 99px;
      border: 1px solid rgba(255,255,255,.30);
      gap: 2px;
    }
    .pill {
      padding: 6px 18px;
      border-radius: 99px;
      border: none;
      font-family: 'DM Sans', sans-serif;
      font-size: 12.5px;
      font-weight: 500;
      cursor: pointer;
      background: transparent;
      color: rgba(255,255,255,.85);
      transition: all .2s;
      text-decoration: none;
    }
    .pill:hover  { background: rgba(255,255,255,.15); color: #ffffff; }
    .pill.active {
      background: #ffffff;
      color: var(--lbl);
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(0,0,0,.20);
    }

    /* Laravel flash error */
    .alert-error {
      background: rgba(184,64,64,.08);
      border: 1px solid rgba(184,64,64,.25);
      border-radius: 10px;
      padding: 12px 14px;
      margin-bottom: 18px;
      font-size: 13px;
      color: var(--red);
    }
  </style>
</head>
<body>

<div id="page-auth">

  <!-- Logo -->
  <div class="logo-area">
    <div class="logo-word">EcoEats</div>
    <div class="logo-tag">Makan Murah Penting Kenyang</div>
  </div>

  <!-- Auth card -->
  <div class="auth-card fade-in">

    {{-- Flash error (wrong credentials etc) --}}
    @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
      <div class="alert-error">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    @yield('content')

  </div>

  <!-- Role switcher -->
  <div class="role-switch">
    <span>Login sebagai:</span>
    <div class="pills-wrap">
      <a href="{{ route('merchant.login') }}"
         class="pill @yield('active-merchant')">Merchant</a>
      <a href="{{ route('admin.login') }}"
         class="pill @yield('active-admin')">Admin</a>
    </div>
  </div>

</div>

</body>
</html>