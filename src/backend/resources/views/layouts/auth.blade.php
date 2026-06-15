<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EcoEats') — Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --olive:        #5F6F52;   /* Dark Olive Green */
            --laurel:       #A9B388;   /* Laurel Green */
            --cornsilk:     #FEFAE0;   /* Cornsilk */
            --camel:        #B99470;   /* Camel */
            --charcoal:     #382C23;   /* Charcoal Brown */

            /* Semantic aliases */
            --bg:           var(--cornsilk);
            --bg-input:     #f5f0d8;
            --bg-border:    #e8e2c8;
            --card-bg:      #ffffff;
            --text-dark:    var(--charcoal);
            --text-mid:     var(--olive);
            --text-muted:   var(--camel);
            --accent:       var(--olive);
            --accent-hover: var(--charcoal);
            --accent-light: var(--laurel);
            --error:        #a13a2a;
            --white:        #ffffff;

            --font-display: 'Fraunces', Georgia, serif;
            --font-body:    'Plus Jakarta Sans', sans-serif;
        }

        /* Bug Fixed */
        body {
            font-family: var(--font-body);
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;   
            align-items: center;
            justify-content: flex-start; 
            padding: 2rem 1.5rem;     /
            position: relative;
        }

        /* Background decorative blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
        }
        body::before {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(169,179,136,0.2) 0%, transparent 70%);
            top: -150px; right: -150px;
        }
        body::after {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(185,148,112,0.15) 0%, transparent 70%);
            bottom: -100px; left: -100px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
            animation: fadeUp 0.5s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Brand header */
        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            margin-bottom: 0.5rem;
        }
        .brand-icon {
            width: 42px; height: 42px;
            background: var(--olive);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }
        .brand-name {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--charcoal);
            letter-spacing: -0.02em;
        }
        .brand-tagline {
            font-size: 0.8rem;
            color: var(--camel);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Card */
        .auth-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2.25rem 2.5rem;
            box-shadow:
                0 1px 2px rgba(56,44,35,0.06),
                0 8px 32px rgba(56,44,35,0.1),
                0 0 0 1px rgba(56,44,35,0.05);
        }

        .card-header {
            margin-bottom: 1.75rem;
        }
        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 0.9rem;
            background: @yield('badge-bg', 'rgba(95,111,82,0.1)');
            color: @yield('badge-color', '#5F6F52');
            border: 1px solid @yield('badge-border', 'rgba(95,111,82,0.25)');
        }
        .card-title {
            font-family: var(--font-display);
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--charcoal);
            line-height: 1.2;
            margin-bottom: 0.35rem;
        }
        .card-subtitle {
            font-size: 0.875rem;
            color: var(--camel);
        }

        /* Alert errors */
        .alert-error {
            background: rgba(161,58,42,0.06);
            border: 1px solid rgba(161,58,42,0.2);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
            color: var(--error);
        }
        .alert-error ul { list-style: none; }
        .alert-error li + li { margin-top: 0.25rem; }

        /* Form */
        .form-group {
            margin-bottom: 1.1rem;
        }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--olive);
            margin-bottom: 0.45rem;
            letter-spacing: 0.01em;
        }
        .form-input {
            width: 100%;
            padding: 0.7rem 0.95rem;
            font-family: var(--font-body);
            font-size: 0.9rem;
            color: var(--charcoal);
            background: var(--bg-input);
            border: 1.5px solid var(--bg-border);
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .form-input:focus {
            border-color: var(--laurel);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(169,179,136,0.2);
        }
        .form-input.is-invalid {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(161,58,42,0.1);
        }
        .field-error {
            font-size: 0.78rem;
            color: var(--error);
            margin-top: 0.35rem;
        }

        /* Remember + forgot */
        .form-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.83rem;
            color: var(--camel);
            cursor: pointer;
        }
        .checkbox-label input[type="checkbox"] {
            accent-color: var(--olive);
            width: 15px; height: 15px;
        }
        .forgot-link {
            font-size: 0.83rem;
            color: var(--olive);
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            font-family: var(--font-body);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            color: var(--white);
            background: var(--olive);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(95,111,82,0.35);
        }
        .btn-submit:hover {
            background: var(--charcoal);
            box-shadow: 0 4px 20px rgba(56,44,35,0.35);
        }
        .btn-submit:active { transform: scale(0.98); }

        /* Portal switcher */
        .portal-switcher {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--bg-border);
            text-align: center;
        }
        .portal-switcher p {
            font-size: 0.8rem;
            color: var(--camel);
            margin-bottom: 0.75rem;
        }
        .portal-links {
            display: flex;
            gap: 0.6rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .portal-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            border: 1.5px solid var(--bg-border);
            color: var(--olive);
            background: var(--bg);
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }
        .portal-link:hover {
            border-color: var(--laurel);
            color: var(--olive);
            background: rgba(169,179,136,0.15);
        }
        .portal-link.active {
            border-color: var(--olive);
            color: var(--olive);
            background: rgba(95,111,82,0.1);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-wrapper">
        <div class="brand">
            <a href="/" class="brand-logo">
                <span class="brand-icon">icon</span>
                <span class="brand-name">EcoEats</span>
            </a>
            <p class="brand-tagline">Makanan surplus, harga ramah</p>
        </div>

        <div class="auth-card">
            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        <div style="text-align:center; margin-top:1.5rem;">
            <div class="portal-switcher" style="border:none; padding:0; margin:0;">
                <p>Login sebagai:</p>
                <div class="portal-links">
                    <!-- <a href="{{ route('login') }}"
                       class="portal-link @yield('active-user')">
                        User
                    </a> -->
                    <a href="{{ route('merchant.login') }}"
                       class="portal-link @yield('active-merchant')">
                        Merchant
                    </a>
                    <a href="{{ route('admin.login') }}"
                       class="portal-link @yield('active-admin')">
                        Admin
                    </a>
                </div>
            </div>        {{-- Link daftar akun baru --}}
        <div style="text-align:center; margin-top:1rem;">
            <div style="font-size:.75rem; color:var(--text-muted);">
                Belum punya akun?
            </div>
            <div style="display:flex; justify-content:center; gap:1rem; margin-top:.5rem;">
                <!-- <a href="{{ route('register') }}"
                   style="font-size:.75rem; color:var(--accent); font-weight:600; text-decoration:none;">
                    Daftar sebagai User
                </a> -->
                <!-- <span style="color:var(--bg-border)">|</span> -->
                <a href="{{ route('merchant.register') }}"
                   style="font-size:.75rem; color:var(--camel); font-weight:600; text-decoration:none;">
                    Daftar sebagai Merchant
                </a>
            </div>
        </div>
 
    </div>
</body>
</html>