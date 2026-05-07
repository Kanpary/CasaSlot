# PHP Casino

A PHP-based online casino web application with MariaDB backend, featuring user management, game integration, payment processing (PIX), and an admin panel.

## Run & Operate

- **Start**: `bash start.sh` — starts MariaDB and PHP built-in server on port 5000
- **Admin panel**: `/02071995admin/` (login: admin@gmail.com / admin123)
- **DB**: MariaDB socket at `/tmp/mysql_run/mysql.sock`, port 3307, database `casino`

## Stack

- **Runtime**: PHP 8.2 (built-in dev server)
- **Database**: MariaDB 10.11 (via local socket)
- **Frontend**: Vue SPA (served from `index.php`)
- **Build tool**: None (pre-built assets in `assets/`)

## Where things live

- `index.php` — main casino Vue SPA entry point
- `router.php` — PHP built-in server router (URL rewriting)
- `config.php` — defines `DASH` constant (admin dir name)
- `02071995admin/` — admin panel
- `02071995admin/services/database.php` — DB connection config
- `api/v1/api.php` — main API (mounted at `/hall/`)
- `assets/` — compiled frontend assets
- `DB.sql` — full database schema + seed data

## Architecture decisions

- PHP built-in server used with a custom `router.php` for URL rewriting (replaces Apache/.htaccess)
- MariaDB runs in-process via socket at `/tmp/mysql_run/mysql.sock` (no system service)
- Admin panel lives under a secret path defined by `DASH` constant in `config.php`
- `start.sh` handles DB initialization, schema import, and admin user seeding on first run

## Product

- Casino game lobby with multiple game provider integrations (GGPix, PlayFiver, etc.)
- PIX payment processing (deposit/withdrawal)
- User registration, login, and wallet management
- Admin panel for site configuration, user management, and financial reports

## User preferences

_None recorded yet_

## Gotchas

- `start.sh` must run from workspace root (`/home/runner/workspace`)
- MariaDB is initialized fresh into `/tmp/mysql_data` — data is lost on container restart
- The `.user.ini` `open_basedir` references the old server path; PHP CLI server ignores it
- Port 3307 used for MariaDB (not 3306) to avoid conflicts

## Pointers

- Workflows skill: `.local/skills/workflows/SKILL.md`
- Deployment skill: `.local/skills/deployment/SKILL.md`
