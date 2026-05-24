<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User — EcoEats</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=Fraunces:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #FEFAE0; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: white; border-radius: 20px; padding: 2.5rem; text-align: center; box-shadow: 0 8px 32px rgba(56,44,35,0.1); max-width: 400px; width: 90%; }
        h1 { font-family: 'Fraunces', serif; color: #382C23; font-size: 1.8rem; margin-bottom: .5rem; }
        p { color: #B99470; margin-bottom: 1.5rem; }
        .badge { display: inline-block; background: rgba(95,111,82,0.1); color: #5F6F52; padding: .3rem .8rem; border-radius: 999px; font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 1rem; }
        form button { padding: .65rem 1.5rem; background: #5F6F52; color: white; border: none; border-radius: 10px; font-family: inherit; font-weight: 600; cursor: pointer; }
        form button:hover { background: #382C23; }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">👤 User</span>
        <h1>Dashboard User</h1>
        <p>Halo, {{ auth()->user()->name }}! Temukan makanan surplus di sekitarmu.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>