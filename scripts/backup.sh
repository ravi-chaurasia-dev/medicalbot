#!/usr/bin/env bash
set -euo pipefail

# Simple MySQL backup script
DB_HOST=${DB_HOST:-localhost}
DB_PORT=${DB_PORT:-3306}
DB_NAME=${DB_DATABASE:-mediai}
DB_USER=${DB_USERNAME:-root}
DB_PASS=${DB_PASSWORD:-}
OUT_DIR=${1:-./backups}

mkdir -p "$OUT_DIR"
FILE="$OUT_DIR/${DB_NAME}_$(date +%F_%H%M%S).sql.gz"

mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" | gzip > "$FILE"
echo "Backup saved to $FILE"
