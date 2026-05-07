#!/usr/bin/env bash
set -e

MYSQL_BIN=/nix/store/a4jsa8kjdn3wlccj2wkvhxqza38rpxzf-mariadb-server-10.11.13/bin
MYSQL_DATA=/tmp/mysql_data
MYSQL_RUN=/tmp/mysql_run
CASINO_DIR="/home/runner/workspace"
MYSQL_CMD="$MYSQL_BIN/mysql --socket=$MYSQL_RUN/mysql.sock -u root"

mkdir -p "$MYSQL_RUN" "$MYSQL_DATA"

# Remove stale socket/pid from previous runs
rm -f "$MYSQL_RUN/mysql.sock" "$MYSQL_RUN/mysql.pid"

# Initialize DB if needed
if [ ! -f "$MYSQL_DATA/mysql/global_priv.frm" ] && [ ! -f "$MYSQL_DATA/mysql/global_priv.ibd" ]; then
  echo "[start] Initializing MariaDB data directory..."
  "$MYSQL_BIN/mariadb-install-db" \
    --user="$(whoami)" \
    --datadir="$MYSQL_DATA" \
    --skip-test-db \
    2>&1 | tail -3 || true
fi

echo "[start] Starting MariaDB..."
"$MYSQL_BIN/mysqld" \
  --user="$(whoami)" \
  --datadir="$MYSQL_DATA" \
  --socket="$MYSQL_RUN/mysql.sock" \
  --port=3307 \
  --pid-file="$MYSQL_RUN/mysql.pid" \
  --log-error="$MYSQL_RUN/mysql.log" \
  --skip-name-resolve \
  --skip-grant-tables \
  --bind-address=127.0.0.1 &

# Wait for socket to be ready
echo "[start] Waiting for MariaDB..."
for i in $(seq 1 60); do
  if [ -S "$MYSQL_RUN/mysql.sock" ]; then
    if $MYSQL_CMD -e "SELECT 1;" >/dev/null 2>&1; then
      echo "[start] MariaDB ready after ${i}s"
      break
    fi
  fi
  sleep 1
done

# Create database and import schema if not exists
DB_EXISTS=$($MYSQL_CMD -e "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME='casino';" 2>/dev/null | grep -c "casino" || true)
DB_EXISTS=${DB_EXISTS:-0}
if [ "$DB_EXISTS" = "0" ]; then
  echo "[start] Creating casino database..."
  $MYSQL_CMD -e "CREATE DATABASE casino CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
  if [ -f "$CASINO_DIR/DB.sql" ]; then
    echo "[start] Importing schema..."
    $MYSQL_CMD casino < "$CASINO_DIR/DB.sql" 2>/dev/null || $MYSQL_CMD --force casino < "$CASINO_DIR/DB.sql" 2>/dev/null || true
    echo "[start] Schema imported OK"
  fi
else
  echo "[start] Database already exists, skipping import"
fi

# Ensure admin user exists (admin@gmail.com / admin123)
$MYSQL_CMD casino -e "
  INSERT INTO admin_users (id, nome, email, contato, senha, nivel, status, token_recover, avatar, 2fa)
  VALUES (1, 'Admin', 'admin@gmail.com', NULL, '\$2a\$12\$bMYnrKRuejID3CUVjARXP.Dyb7xT4dlX9un07SLQmyWUoyoOHbQv2', 0, 1, NULL, NULL, NULL)
  ON DUPLICATE KEY UPDATE status=1, senha='\$2a\$12\$bMYnrKRuejID3CUVjARXP.Dyb7xT4dlX9un07SLQmyWUoyoOHbQv2', 2fa=NULL;
" 2>/dev/null && echo "[start] Admin user ensured (admin@gmail.com / admin123)"

# Ensure canvas_slot_config table and game exist
$MYSQL_CMD casino -e "
CREATE TABLE IF NOT EXISTS canvas_slot_config (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome_jogo VARCHAR(255) NOT NULL DEFAULT 'Fortune Tiger Canvas',
  descricao TEXT,
  status TINYINT(1) NOT NULL DEFAULT 1,
  popular TINYINT(1) NOT NULL DEFAULT 1,
  aposta_minima DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  aposta_maxima DECIMAL(10,2) NOT NULL DEFAULT 500.00,
  rtp DECIMAL(5,2) NOT NULL DEFAULT 96.00,
  simbolos JSON,
  multiplicadores JSON,
  banner_url VARCHAR(500) DEFAULT '/slot_canvas/banner.png',
  cor_fundo VARCHAR(20) DEFAULT '#1a0a2e',
  cor_primaria VARCHAR(20) DEFAULT '#f0a500',
  efeitos_sonoros TINYINT(1) DEFAULT 1,
  modo_demo TINYINT(1) DEFAULT 1,
  atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT IGNORE INTO canvas_slot_config (id, nome_jogo, descricao, simbolos, multiplicadores)
VALUES (1, 'Fortune Tiger Canvas', 'Slot exclusivo com animação Canvas HTML5.',
  JSON_ARRAY('🐯','💎','🍋','🍇','🔔','⭐','🎰','💰','🃏','❤️'),
  JSON_OBJECT('3x_same','5','4x_same','15','5x_same','50','3x_wild','10','4x_wild','25','5x_wild','100')
);
INSERT IGNORE INTO games (id, game_code, game_name, banner, status, provider, popular, type, game_type, api)
VALUES (9999999, 'canvas-slot', 'Fortune Tiger Canvas', '/slot_canvas/banner.png', 1, 'CanvasSlot', 1, 'slot', '1', 'CanvasSlot');
" 2>/dev/null && echo "[start] Canvas Slot ensured"

# Remove MaxAPIGames games (paid provider — not free)
$MYSQL_CMD casino -e "
  DELETE FROM games WHERE api = 'MaxAPIGames';
" 2>/dev/null && echo "[start] MaxAPIGames games removed"

# Add Slotopol games (free, open-source game engine) — idempotent: delete+insert
$MYSQL_CMD casino -e "
DELETE FROM games WHERE provider = 'Slotopol' AND id >= 10000000;
INSERT INTO games (id, game_code, game_name, banner, status, provider, popular, type, game_type, api) VALUES
  (10000000, 'slotopol-aztec',    'Aztec Coins',    '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
  (10000001, 'slotopol-book',     'Book of Ra',     '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
  (10000002, 'slotopol-monkey',   'Crazy Monkey',   '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
  (10000003, 'slotopol-fruit',    'Fruit Cocktail', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
  (10000004, 'slotopol-garage',   'Garage',         '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
  (10000005, 'slotopol-haunter',  'Lucky Haunter',  '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
  (10000006, 'slotopol-resident', 'Resident',       '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
  (10000007, 'slotopol-shaman',   'Shaman',         '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
  (10000008, 'slotopol-sweet',    'Sweet Life',     '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol');
" 2>/dev/null && echo "[start] Slotopol games ensured"

# Create test user (mobile: 11999999999 / senha: admin123)
$MYSQL_CMD casino -e "
  INSERT INTO usuarios
    (mobile, celular, password, saldo, saldo_afiliados, rev, total_rev, real_name, spassword, url, token, invite_code)
  VALUES
    ('11999999999','11999999999','\$2a\$12\$bMYnrKRuejID3CUVjARXP.Dyb7xT4dlX9un07SLQmyWUoyoOHbQv2',
     100.00, 0, 0, 0, 'Teste', 'admin123', '', MD5(RAND()), 'TESTE001')
  ON DUPLICATE KEY UPDATE saldo=100.00;
" 2>/dev/null && echo "[start] Test user ensured (mobile: 11999999999 / senha: admin123)"

echo "[start] Starting PHP server on port 5001..."

# Download ionCube loader if not present
IONCUBE_SO="$CASINO_DIR/ioncube/ioncube_loader_lin_8.2.so"
if [ ! -f "$IONCUBE_SO" ]; then
  echo "[start] Downloading ionCube loader..."
  mkdir -p "$CASINO_DIR/ioncube"
  curl -sL "https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz" -o /tmp/ioncube.tar.gz
  tar -xzf /tmp/ioncube.tar.gz -C /tmp/
  cp /tmp/ioncube/ioncube_loader_lin_8.2.so "$IONCUBE_SO"
  echo "[start] ionCube loader installed"
fi

# Start Slotopol game server (free open-source slot engine)
mkdir -p "$CASINO_DIR/slotopol/sqlite"
# Pre-create valid SQLite databases (slotopol's Go XORM cannot create new files on NixOS)
python3 -c "
import sqlite3, os
for p in ['$CASINO_DIR/slotopol/sqlite/slot-club.sqlite',
          '$CASINO_DIR/slotopol/sqlite/slot-spin.sqlite']:
    if not os.path.exists(p) or os.path.getsize(p) == 0:
        c = sqlite3.connect(p); c.commit(); c.close()
        print('[start] Created SQLite:', p.split('/')[-1])
" 2>/dev/null || true

if [ -x "$CASINO_DIR/slotopol/slot_server" ]; then
  cd "$CASINO_DIR/slotopol"
  ./slot_server web \
    --config config/slot-app.yaml \
    >> /tmp/slotopol.log 2>&1 &
  SLOTOPOL_PID=$!
  cd "$CASINO_DIR"
  sleep 5

  # Check if slotopol is running
  if kill -0 $SLOTOPOL_PID 2>/dev/null && curl -sf http://127.0.0.1:5002/ping -o /dev/null 2>/dev/null; then
    echo "[start] Slotopol game server OK (port 5002, pid=$SLOTOPOL_PID)"

    # Register admin user and set a large wallet for game sessions
    python3 -c "
import urllib.request, urllib.error, json, base64, hmac, hashlib, time, sqlite3, os

SLOTOPOL = 'http://127.0.0.1:5002'
ACCESS_KEY = 'CasaSlotAccessKey2024xJgM4NsbP3fs4k7vh0gfdkgGl8dJ'
SQLITE = '$CASINO_DIR/slotopol/sqlite/slot-club.sqlite'
LARGE_WALLET = 1000000000  # 1 billion coins = R\$10M buffer

def sp_post(path, body, token=None):
    try:
        req = urllib.request.Request(SLOTOPOL + path,
            data=json.dumps(body).encode(),
            headers={'Content-Type':'application/json',
                     **(({'Authorization':'Bearer '+token}) if token else {})},
            method='POST')
        r = urllib.request.urlopen(req, timeout=5)
        return json.loads(r.read())
    except Exception as e:
        return {}

# STEP 1: Pre-set wallet in SQLite BEFORE signup (server reads from SQLite on load)
try:
    db = sqlite3.connect(SQLITE)
    db.execute(f'UPDATE props SET wallet={LARGE_WALLET}, utime=datetime(\"now\") WHERE cid=1')
    db.commit()
    db.close()
    print(f'[slotopol] SQLite wallet pre-set to {LARGE_WALLET} coins')
except Exception as e:
    print(f'[slotopol] SQLite pre-set error: {e}')

# STEP 2: Signup/load admin user into server memory (server reads wallet from SQLite now)
r = sp_post('/signup', {'email':'admin@casaslot.local','pass':'slotadmin2024','name':'Admin','secret':ACCESS_KEY})
uid = r.get('uid', 0)
if uid:
    print(f'[slotopol] Admin registered uid={uid}')
else:
    r2 = sp_post('/signis', {'email':'admin@casaslot.local'})
    uid = r2.get('uid', 1)
    print(f'[slotopol] Admin already exists uid={uid}')

print(f'[slotopol] Ready: admin uid={uid}, wallet={LARGE_WALLET} coins in memory')
" 2>/dev/null || echo "[start] Slotopol init script error (non-fatal)"

  else
    echo "[start] Slotopol failed to start (check /tmp/slotopol.log)"
  fi
else
  echo "[start] Slotopol binary not found, skipping"
fi

# GitHub auto-sync: real-time via inotifywait (triggers on every file change)
if [ -n "$GITHUB_TOKEN" ]; then
    bash "$CASINO_DIR/github-sync.sh" >> /tmp/github-sync.log 2>&1 || true
    (
      inotifywait -m -r -e modify,create,delete,move \
        --exclude '(\.git|/tmp|ioncube|slotopol/sqlite|\.log$)' \
        "$CASINO_DIR" 2>/dev/null |
      while read -r dir event file; do
        bash "$CASINO_DIR/github-sync.sh" >> /tmp/github-sync.log 2>&1 || true
      done
    ) &
    echo "[start] GitHub real-time sync enabled (inotifywait)"
else
    echo "[start] GITHUB_TOKEN not set — GitHub sync disabled"
fi

# Start PHP on port 5001 (externalPort 80 — public URL access)
php -c "$CASINO_DIR/php.ini" -S 0.0.0.0:5001 -t "$CASINO_DIR" "$CASINO_DIR/router.php" >> /tmp/php5001.log 2>&1 &
echo "[start] PHP server also on port 5001 (external access)"

# Start PHP on port 5000 (Replit preview pane — waitForPort)
exec php -c "$CASINO_DIR/php.ini" -S 0.0.0.0:5000 -t "$CASINO_DIR" "$CASINO_DIR/router.php"
