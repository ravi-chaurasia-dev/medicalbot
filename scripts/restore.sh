#!/usr/bin/env bash
set -euo pipefail

# Usage: restore.sh path/to/backup.sql.gz
if [ $# -lt 1 ]; then
  echo "Usage: $0 path/to/backup.sql.gz"
  exit 1
fi

FILE=$1
DB_HOST=${DB_HOST:-localhost}
DB_PORT=${DB_PORT:-3306}
DB_NAME=${DB_DATABASE:-mediai}
DB_USER=${DB_USERNAME:-root}
DB_PASS=${DB_PASSWORD:-}

gunzip -c "$FILE" | mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME"
echo "Restore complete."
