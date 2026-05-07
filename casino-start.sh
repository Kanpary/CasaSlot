#!/usr/bin/env bash
MYSQL_RUN=/tmp/mysql_run
CASINO_DIR=/home/runner/workspace/artifacts/php-casino
MYSQL_CMD="mysql --socket=$MYSQL_RUN/mysql.sock -u root"

echo "[casino] Waiting for MySQL socket..."
for i in $(seq 1 60); do
  if [ -S "$MYSQL_RUN/mysql.sock" ]; then
    if $MYSQL_CMD -e "SELECT 1;" >/dev/null 2>&1; then
      echo "[casino] MySQL is up after ${i}s"
      break
    fi
  fi
  sleep 1
done

# Setup database if not exists
DB_EXISTS=$($MYSQL_CMD -e "SHOW DATABASES LIKE 'casino';" 2>/dev/null | grep -c "casino" || echo "0")
if [ "$DB_EXISTS" = "0" ]; then
  echo "[casino] Creating casino database..."
  $MYSQL_CMD -e "CREATE DATABASE IF NOT EXISTS casino CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;" 2>&1
  echo "[casino] Importing schema..."
  $MYSQL_CMD casino < "$CASINO_DIR/DB.sql" 2>&1 && echo "[casino] Schema imported OK"
else
  echo "[casino] Database already exists"
fi

# Ensure admin user exists with password 'admin123' (hash from DB.sql)
$MYSQL_CMD casino -e "
  INSERT INTO admin_users (id, nome, email, contato, senha, nivel, status, token_recover, avatar, 2fa)
  VALUES (1, 'admin', 'admin@gmail.com', NULL, '\$2a\$12\$bMYnrKRuejID3CUVjARXP.Dyb7xT4dlX9un07SLQmyWUoyoOHbQv2', 0, 1, NULL, NULL, NULL)
  ON DUPLICATE KEY UPDATE status=1, senha='\$2a\$12\$bMYnrKRuejID3CUVjARXP.Dyb7xT4dlX9un07SLQmyWUoyoOHbQv2', 2fa=NULL;
" 2>/dev/null && echo "[casino] Admin user ensured (email: admin@gmail.com, password: admin123)"

echo "[casino] Starting PHP server on port ${PORT:-9000}..."
exec php -S 0.0.0.0:${PORT:-9000} -t "$CASINO_DIR" "$CASINO_DIR/router.php"
