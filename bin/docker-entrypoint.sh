#!/bin/bash
set -e

echo "====================================="
echo "Starting Web Container Entrypoint"
echo "====================================="

PROJECT_ROOT="/var/www/html"
cd "$PROJECT_ROOT"

if [ -f "$PROJECT_ROOT/bin/reset_db-container.sh" ]; then
    echo "Running database reset script..."
    bash "$PROJECT_ROOT/bin/reset_db-container.sh"
    echo "Database reset completed"
else
    echo "Warning: bin/reset_db-container.sh not found, skipping database reset"
fi

echo "Starting Apache..."
exec apache2-foreground
