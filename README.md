# Brill Lash and Beauty

Laravel/Vite landing page for Brill Lash and Beauty, based on the same production stack as the Across project while preserving the existing Brill design.

## Stack

- Laravel 12
- PHP 8.3 FPM
- Nginx
- MariaDB 11.4
- Vite
- Docker Compose
- No Laravel Sail

## Local start

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec node npm run build
```

Open:

```text
http://localhost:8081
```

For Vite development, run:

```bash
docker compose --profile assets up -d node
docker compose exec node npm run dev -- --host 0.0.0.0
```

Vite is available on:

```text
http://localhost:5173
```

MariaDB is exposed on host port `3307` to avoid clashing with a local database already using `3306`.

## Useful commands

```bash
docker compose ps
docker compose logs -f app
docker compose logs -f nginx
docker compose exec app php artisan optimize:clear
docker compose exec node npm run build
```

## Assets

The landing page uses the Brill assets in:

```text
public/assets
```

## Production notes

Before going live, set the production `.env` carefully:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:strong_generated_key_here
ADMIN_PASSWORD=change_this_to_a_long_unique_password
APP_URL=https://your-live-domain.example
```

- Use a strong generated `APP_KEY` and never reuse local values.
- Generate a strong, unique `ADMIN_PASSWORD`; never use `brill-admin` on a live site.
- Set `APP_ENV=production` and `APP_DEBUG=false` before launch.
- Serve the site over HTTPS only.
- Keep `.env` out of git; `.env.example` should contain placeholders only.
- Make sure the PHP user can write to `storage/`, especially `storage/app/site-content.json` and `storage/app/site-content-backups/`.
- Protect `storage/app/` from public web access.
