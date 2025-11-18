#!/bin/bash

set -e

PROJECT_ROOT="/var/www/html"
cd "$PROJECT_ROOT"

DB_NAME="shoe"
DB_USER="shoes_user"
DB_PASS="shoes_pass"
DB_HOST="mysql"
MIGRATIONS_DIR="assets/config/mysql/migrations"
SEEDERS_DIR="assets/config/mysql/seeders"
BASE_SQL="assets/config/mysql/shoe.sql"

echo "====================================="
echo "MySQL Database Full Refresh (Container)"
echo "====================================="

echo "Waiting for MySQL to be ready..."
until mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "SELECT 1" &>/dev/null; do
    echo "Waiting for MySQL..."
    sleep 2
done
echo "MySQL is ready"

echo "Dropping and recreating database $DB_NAME..."
mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "DROP DATABASE IF EXISTS \`$DB_NAME\`; CREATE DATABASE \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo "Database recreated"

if [ -f "$BASE_SQL" ]; then
    echo "Importing base schema and data from $BASE_SQL..."
    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$BASE_SQL"
    echo "Base data imported"
else
    echo "Warning: Base SQL file not found at $BASE_SQL, skipping import"
fi

echo "Running migrations..."
if [ -d "$MIGRATIONS_DIR" ]; then
    for file in $(ls -1 "$MIGRATIONS_DIR"/*.sql 2>/dev/null | sort); do
        filename=$(basename "$file")
        echo "Running migration: $filename"
        mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$file"
        echo "Migration $filename completed"
    done
    echo "All migrations completed"
else
    echo "Warning: Migrations directory not found, skipping migrations"
fi

echo "Running seeders..."
if [ -d "$SEEDERS_DIR" ]; then
    for file in $(find "$SEEDERS_DIR" -name "*.sql" -not -name "*.down.sql" | sort); do
        filename=$(basename "$file")
        echo "Running seeder: $filename"
        mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$file"
        echo "Seeder $filename completed"
    done
    echo "All seeders completed"
else
    echo "Warning: Seeders directory not found, skipping seeders"
fi

echo "====================================="
echo "Database refresh finished"
echo "====================================="
