<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Réinitialiser mes accès — {{ config('app.name', 'DikiTivi') }}</title>
    <style>
        :root {
            --bg: #f3f4f6;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
            --primary: #d97706;
            --primary-hover: #b45309;
            --danger: #b91c1c;
            --ok: #047857;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(160deg, #fff7ed 0%, var(--bg) 45%, #e5e7eb 100%);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .card {
            width: 100%;
            max-width: 28rem;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,.06);
        }
        .brand {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        .brand img {
            height: 3.5rem;
            width: auto;
        }
        h1 {
            margin: 0 0 0.5rem;
            font-size: 1.25rem;
            font-weight: 700;
        }
        .hint {
            margin: 0 0 1.25rem;
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.45;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }
        input {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 1rem;
            margin-bottom: 0.35rem;
        }
        input:focus {
            outline: 2px solid rgba(217, 119, 6, 0.35);
            border-color: var(--primary);
        }
        .field { margin-bottom: 1rem; }
        .error {
            color: var(--danger);
            font-size: 0.8rem;
            margin: 0.15rem 0 0;
        }
        .status {
            background: #ecfdf5;
            color: var(--ok);
            border: 1px solid #a7f3d0;
            border-radius: 0.5rem;
            padding: 0.65rem 0.75rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1.25rem;
            flex-wrap: wrap;
        }
        .link {
            color: var(--muted);
            font-size: 0.875rem;
            text-decoration: underline;
        }
        button {
            border: none;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            padding: 0.7rem 1.1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        button:hover { background: var(--primary-hover); }
        .code-input {
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            @if (file_exists(public_path('assets/images/logo.png')))
                <img src="{{ asset('assets/images/logo.png') }}" alt="DikiTivi">
            @endif
        </div>

        <h1>Créer un nouveau mot de passe</h1>
        <p class="hint">
            Saisissez l’e-mail de votre compte agent, le <strong>code unique</strong> reçu par e-mail, puis choisissez un nouveau mot de passe.
        </p>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('access.reset.store') }}">
            @csrf

            <div class="field">
                <label for="email">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email', $email ?? '') }}" required autofocus autocomplete="username">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="code">Code unique</label>
                <input id="code" class="code-input" type="text" name="code" value="{{ old('code') }}" required autocomplete="one-time-code" maxlength="16">
                @error('code') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Nouveau mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="actions">
                <a class="link" href="{{ url('/admin/login') }}">Retour à la connexion</a>
                <button type="submit">Enregistrer le mot de passe</button>
            </div>
        </form>
    </div>
</body>
</html>
