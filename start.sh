#!/usr/bin/env bash
set -e

MYSQL_DATA=/tmp/mysql_data
MYSQL_RUN=/tmp/mysql_run
CASINO_DIR="/home/runner/workspace/artifacts/php-casino"
MYSQL_CMD="mysql --socket=$MYSQL_RUN/mysql.sock -u root"

mkdir -p "$MYSQL_RUN" "$MYSQL_DATA"

# Remove stale socket/pid from previous runs
rm -f "$MYSQL_RUN/mysql.sock" "$MYSQL_RUN/mysql.pid"

# Initialize DB if needed
if [ ! -f "$MYSQL_DATA/mysql/global_priv.frm" ] && [ ! -f "$MYSQL_DATA/mysql/global_priv.ibd" ]; then
  echo "[start] Initializing MariaDB data directory..."
  mariadb-install-db \
    --user="$(whoami)" \
    --datadir="$MYSQL_DATA" \
    --skip-test-db \
    2>&1 | tail -3 || true
fi

echo "[start] Starting MariaDB..."
mysqld \
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
DB_EXISTS=$($MYSQL_CMD -e "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME='casino';" 2>/dev/null | grep -c "casino" || echo "0")
if [ "$DB_EXISTS" = "0" ]; then
  echo "[start] Creating casino database..."
  $MYSQL_CMD -e "CREATE DATABASE casino CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
  if [ -f "$CASINO_DIR/DB.sql" ]; then
    echo "[start] Importing schema..."
    $MYSQL_CMD casino < "$CASINO_DIR/DB.sql" && echo "[start] Schema imported OK"
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

echo "[start] Starting PHP server on port ${PORT:-9000}..."

# Auto-sync to GitHub in background (after 10s delay to let PHP settle)
if [ -n "$GITHUB_TOKEN" ]; then
    (sleep 10 && bash "$CASINO_DIR/github-sync.sh" >> /tmp/github-sync.log 2>&1 && echo "[github-sync] Done") &
    echo "[start] GitHub auto-sync scheduled in background..."
else
    echo "[start] GITHUB_TOKEN not set — skipping GitHub sync"
fi

exec php -S 0.0.0.0:${PORT:-9000} -t "$CASINO_DIR" "$CASINO_DIR/router.php"
