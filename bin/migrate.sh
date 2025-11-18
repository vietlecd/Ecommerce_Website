#!/bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
cd "$PROJECT_ROOT"

MIGRATIONS_DIR="assets/config/mysql/migrations"
DOCKER_SERVICE="mysql"
DB_USER="shoes_user"
DB_PASS="shoes_pass"
DB_NAME="shoe"

echo "====================================="
echo "MySQL Database Migration Runner"
echo "====================================="

if ! command -v docker &> /dev/null; then
    echo "Error: Docker is not installed or not in PATH"
    exit 1
fi

check_mysql_connection() {
    echo "Checking MySQL connection..."
    
    if ! docker compose exec $DOCKER_SERVICE mysqladmin -u$DB_USER -p$DB_PASS ping --silent &> /dev/null; then
        echo "❌ Cannot connect to MySQL. Is the service running?"
        echo "Try starting the service with: docker compose up -d mysql"
        exit 1
    fi
    
    echo "✅ MySQL connection successful"
}

run_migration() {
    local file=$1
    local filename=$(basename "$file")
    
    echo "Running migration: $filename"
    
    docker compose cp "$file" $DOCKER_SERVICE:/tmp/
    docker compose exec $DOCKER_SERVICE bash -c "mysql -u$DB_USER -p$DB_PASS $DB_NAME < /tmp/$filename"
    
    echo "✅ Migration $filename completed"
}

show_help() {
    echo "Usage: $0 [command] [options]"
    echo "Commands:"
    echo "  all                       Run all migrations"
    echo "  <filename.sql>            Run a specific migration file"
    echo "  rollback [n]              Roll back the last n migrations (default: 1)"
    echo "  reset                     Roll back all migrations"
    echo "  --help, -h                Display this help message"
    echo "  --list, -l                List available migrations"
    echo "  --status, -s              Show migration status"
    echo "Examples:"
    echo "  $0 all                           # Run all migrations"
    echo "  $0 001_create_qna_tables.sql     # Run a specific migration"
    echo "  $0 rollback                      # Roll back the last migration"
    echo "  $0 rollback 3                    # Roll back the last 3 migrations"
    echo "  $0 --list                        # List available migrations"
    exit 1
}

list_migrations() {
    echo "Available migrations in $MIGRATIONS_DIR:"
    echo "----------------------------------------"
    
    if [ ! -d "$MIGRATIONS_DIR" ] || [ -z "$(ls -A $MIGRATIONS_DIR/*.sql 2>/dev/null)" ]; then
        echo "No migration files found."
        exit 0
    fi
    
    i=1
    for file in $(ls -1 $MIGRATIONS_DIR/*.sql | sort); do
        filename=$(basename "$file")
        filesize=$(stat -c %s "$file")
        filedate=$(stat -c %y "$file")
        echo "$i. $filename ($filesize bytes, modified: $filedate)"
        i=$((i+1))
    done
}

if [ $# -eq 0 ]; then
    show_help
fi

case "$1" in
    --help|-h)
        show_help
        ;;
    --list|-l)
        list_migrations
        exit 0
        ;;
    --status|-s)
        check_mysql_connection
        docker compose exec web php /var/www/html/bin/migrate.php --status
        exit $?
        ;;
    rollback)
        check_mysql_connection
        STEPS=${2:-1}
        docker compose exec web php /var/www/html/bin/migrate.php rollback $STEPS
        exit $?
        ;;
    reset)
        check_mysql_connection
        echo "WARNING: This will roll back ALL migrations. All data will be lost."
        read -p "Are you sure you want to continue? (y/N): " confirm
        
        if [ "$confirm" != "y" ] && [ "$confirm" != "Y" ]; then
            echo "Reset canceled."
            exit 0
        fi
        
        docker compose exec web php /var/www/html/bin/migrate.php reset
        exit $?
        ;;
    --*)
        echo "Unknown option: $1"
        echo "Run with --help for usage information."
        exit 1
        ;;
esac

if [ "$1" == "all" ]; then
    echo "Running all migrations in $MIGRATIONS_DIR"
    
    check_mysql_connection
    
    files=($(ls -1 $MIGRATIONS_DIR/*.sql 2>/dev/null | sort))
    
    if [ ${#files[@]} -eq 0 ]; then
        echo "No migration files found in $MIGRATIONS_DIR"
        exit 0
    fi
    
    for file in "${files[@]}"; do
        run_migration "$file"
    done
    
    echo "====================================="
    echo "✅ All migrations completed successfully"
    echo "====================================="
else
    file="$MIGRATIONS_DIR/$1"
    
    if [ ! -f "$file" ]; then
        echo "Error: Migration file not found: $file"
        echo "Run with --list to see available migrations or --help for usage information."
        exit 1
    fi
    
    check_mysql_connection
    run_migration "$file"
fi
