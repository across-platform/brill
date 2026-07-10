<!doctype html>
<html lang="hu">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Brill Admin</title>
    <style>
      :root {
        --ink: #211817;
        --muted: #7b6560;
        --line: #ead5cd;
        --paper: #fffaf6;
        --rose: #c792a2;
        --rose-dark: #9c6f7d;
      }

      * {
        box-sizing: border-box;
      }

      body {
        display: grid;
        min-height: 100vh;
        place-items: center;
        margin: 0;
        color: var(--ink);
        background: linear-gradient(135deg, #fffaf6, #f8efe8);
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      }

      main {
        width: min(420px, calc(100vw - 32px));
        padding: 30px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.78);
        box-shadow: 0 24px 70px rgba(94, 49, 41, 0.14);
      }

      h1 {
        margin: 0 0 8px;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 2rem;
        font-weight: 500;
      }

      p {
        margin: 0 0 22px;
        color: var(--muted);
      }

      label {
        display: grid;
        gap: 8px;
        color: var(--muted);
        font-size: 0.9rem;
        font-weight: 700;
      }

      input {
        width: 100%;
        min-height: 46px;
        padding: 0 14px;
        border: 1px solid var(--line);
        border-radius: 8px;
        color: var(--ink);
        background: #fff;
        font: inherit;
      }

      button {
        width: 100%;
        min-height: 46px;
        margin-top: 18px;
        border: 0;
        border-radius: 999px;
        color: #fff;
        background: var(--rose);
        box-shadow: 0 14px 34px rgba(199, 146, 162, 0.3);
        cursor: pointer;
        font-weight: 850;
        letter-spacing: 0.08em;
        text-transform: uppercase;
      }

      .error {
        margin-top: 12px;
        color: var(--rose-dark);
        font-weight: 700;
      }
    </style>
  </head>
  <body>
    <main>
      <h1>Brill Admin</h1>
      <p>Rejtett szerkesztőfelület a weboldal tartalmához.</p>

      <form method="post" action="{{ route('admin.login') }}">
        @csrf
        <label>
          Admin jelszó
          <input name="password" type="password" autocomplete="current-password" required autofocus />
        </label>
        <button type="submit">Belépés</button>
        @error('password')
          <p class="error">{{ $message }}</p>
        @enderror
      </form>
    </main>
  </body>
</html>
