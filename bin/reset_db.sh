#!/bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
cd "$PROJECT_ROOT"

DB_NAME="shoe"
DB_USER="shoes_user"
DB_PASS="shoes_pass"
DOCKER_SERVICE="mysql"
MIGRATE_SCRIPT="./bin/migrate.sh"
SEED_SCRIPT="./bin/seed.sh"
BASE_SQL="assets/config/mysql/shoe.sql"

echo "====================================="
echo "MySQL Database Full Refresh"
echo "====================================="

if ! command -v docker &> /dev/null; then
    echo "Error: Docker is not installed or not in PATH"
    exit 1
fi

echo "Checking MySQL service..."
if ! docker compose exec $DOCKER_SERVICE mysqladmin -u$DB_USER -p$DB_PASS ping --silent &> /dev/null; then
    echo "Error: Cannot connect to MySQL. Ensure docker compose services are running."
    exit 1
fi
echo "MySQL service is reachable"

echo "Dropping and recreating database $DB_NAME..."
docker compose exec $DOCKER_SERVICE mysql -u$DB_USER -p$DB_PASS -e "DROP DATABASE IF EXISTS \`$DB_NAME\`; CREATE DATABASE \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo "Database recreated"

if [ -f "$BASE_SQL" ]; then
    echo "Importing base schema and data from $BASE_SQL..."
    docker compose cp "$BASE_SQL" $DOCKER_SERVICE:/tmp/shoe.sql
    docker compose exec $DOCKER_SERVICE mysql -u$DB_USER -p$DB_PASS $DB_NAME -e "SOURCE /tmp/shoe.sql"
    echo "Base data imported"
else
    echo "Warning: Base SQL file not found at $BASE_SQL, skipping import"
fi

echo "Running migrations..."
bash "$MIGRATE_SCRIPT" all
echo "Migrations completed"

echo "Running seeders..."
bash "$SEED_SCRIPT" all
echo "Seeders completed"

echo "====================================="
echo "Database refresh finished"
echo "====================================="
