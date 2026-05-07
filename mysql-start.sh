#!/usr/bin/env bash
MYSQL_BIN=/nix/store/a4jsa8kjdn3wlccj2wkvhxqza38rpxzf-mariadb-server-10.11.13/bin
MYSQL_DATA=/tmp/mysql_data
MYSQL_RUN=/tmp/mysql_run

mkdir -p "$MYSQL_RUN" "$MYSQL_DATA"

# Remove stale socket/pid from previous runs
rm -f "$MYSQL_RUN/mysql.sock" "$MYSQL_RUN/mysql.pid"

# Check if already initialized (system tables present)
if [ ! -f "$MYSQL_DATA/mysql/global_priv.frm" ]; then
  echo "[mysql-start] Initializing data directory..."
  # Use mysqld --bootstrap to init without mysql_install_db shell script
  "$MYSQL_BIN/mysqld" \
    --user="$(whoami)" \
    --datadir="$MYSQL_DATA" \
    --bootstrap \
    2>/dev/null <<'SQLEOF'
set sql_mode='';
set storage_engine=MyISAM;
CREATE DATABASE IF NOT EXISTS mysql;
USE mysql;
SQLEOF
  # If still no init, try direct initialize
  if [ ! -f "$MYSQL_DATA/mysql/global_priv.frm" ]; then
    echo "[mysql-start] Trying alternate init..."
    rm -rf "$MYSQL_DATA"/*
    "$MYSQL_BIN/mariadb-install-db" \
      --user="$(whoami)" \
      --datadir="$MYSQL_DATA" \
      --skip-test-db \
      2>&1 | tail -3 || true
  fi
fi

echo "[mysql-start] Starting mysqld..."
exec "$MYSQL_BIN/mysqld" \
  --user="$(whoami)" \
  --datadir="$MYSQL_DATA" \
  --socket="$MYSQL_RUN/mysql.sock" \
  --port=3307 \
  --pid-file="$MYSQL_RUN/mysql.pid" \
  --log-error="$MYSQL_RUN/mysql.log" \
  --skip-grant-tables \
  --skip-name-resolve \
  --bind-address=0.0.0.0
