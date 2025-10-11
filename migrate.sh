#!/bin/bash

# Simple migration runner script for MySQL in Docker
# Usage: ./migrate.sh [all|filename.sql]

set -e

MIGRATIONS_DIR="assets/config/mysql/migrations"
DOCKER_SERVICE="mysql"
DB_USER="shoes_user"
DB_PASS="shoes_pass"
DB_NAME="shoe"

# Print header
echo "====================================="
echo "MySQL Database Migration Runner"
echo "====================================="

# Check if docker compose is available
if ! command -v docker &> /dev/null; then
    echo "Error: Docker is not installed or not in PATH"
    exit 1
fi

# Function to check MySQL connection
check_mysql_connection() {
    echo "Checking MySQL connection..."
    
    # Try to connect to MySQL
    if ! docker compose exec $DOCKER_SERVICE mysqladmin -u$DB_USER -p$DB_PASS ping --silent &> /dev/null; then
        echo "❌ Cannot connect to MySQL. Is the service running?"
        echo "Try starting the service with: docker compose up -d mysql"
        exit 1
    fi
    
    echo "✅ MySQL connection successful"
}

# Function to run a specific migration
run_migration() {
    local file=$1
    local filename=$(basename "$file")
    
    echo "Running migration: $filename"
    
    # Copy the file to the container
    docker compose cp "$file" $DOCKER_SERVICE:/tmp/
    
    # Run the migration
    docker compose exec $DOCKER_SERVICE bash -c "mysql -u$DB_USER -p$DB_PASS $DB_NAME < /tmp/$filename"
    
    echo "✅ Migration $filename completed"
}

# Function to show help
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

# Function to list migrations
list_migrations() {
    echo "Available migrations in $MIGRATIONS_DIR:"
    echo "----------------------------------------"
    
    if [ ! -d "$MIGRATIONS_DIR" ] || [ -z "$(ls -A $MIGRATIONS_DIR/*.sql 2>/dev/null)" ]; then
        echo "No migration files found."
        exit 0
    fi
    
    # List migrations with details
    i=1
    for file in $(ls -1 $MIGRATIONS_DIR/*.sql | sort); do
        filename=$(basename "$file")
        filesize=$(stat -c %s "$file")
        filedate=$(stat -c %y "$file")
        echo "$i. $filename ($filesize bytes, modified: $filedate)"
        i=$((i+1))
    done
}

# Check if an argument was provided
if [ $# -eq 0 ]; then
    show_help
fi

# Check for help or list options
case "$1" in
    --help|-h)
        show_help
        ;;
    --list|-l)
        list_migrations
        exit 0
        ;;
    --status|-s)
        # Check MySQL connection first
        check_mysql_connection
        
        # Run the PHP script for status (since it's easier to handle in PHP)
        docker compose exec web php /var/www/html/bin/migrate.php --status
        exit $?
        ;;
    rollback)
        # Check MySQL connection first
        check_mysql_connection
        
        # Get steps parameter if provided
        STEPS=${2:-1}
        
        # Run the PHP script for rollback
        docker compose exec web php /var/www/html/bin/migrate.php rollback $STEPS
        exit $?
        ;;
    reset)
        # Check MySQL connection first
        check_mysql_connection
        
        echo "WARNING: This will roll back ALL migrations. All data will be lost."
        read -p "Are you sure you want to continue? (y/N): " confirm
        
        if [ "$confirm" != "y" ] && [ "$confirm" != "Y" ]; then
            echo "Reset canceled."
            exit 0
        fi
        
        # Run the PHP script for reset
        docker compose exec web php /var/www/html/bin/migrate.php reset
        exit $?
        ;;
    --*)
        # Handle any other options starting with --
        echo "Unknown option: $1"
        echo "Run with --help for usage information."
        exit 1
        ;;
esac

# Handle the 'all' option
if [ "$1" == "all" ]; then
    echo "Running all migrations in $MIGRATIONS_DIR"
    
    # Check MySQL connection first
    check_mysql_connection
    
    # Get all SQL files and sort them
    files=($(ls -1 $MIGRATIONS_DIR/*.sql 2>/dev/null | sort))
    
    if [ ${#files[@]} -eq 0 ]; then
        echo "No migration files found in $MIGRATIONS_DIR"
        exit 0
    fi
    
    # Run each migration
    for file in "${files[@]}"; do
        run_migration "$file"
    done
    
    echo "====================================="
    echo "✅ All migrations completed successfully"
    echo "====================================="
else
    # Run a specific migration
    file="$MIGRATIONS_DIR/$1"
    
    # Check if the file exists
    if [ ! -f "$file" ]; then
        echo "Error: Migration file not found: $file"
        echo "Run with --list to see available migrations or --help for usage information."
        exit 1
    fi
    
    # Check MySQL connection first
    check_mysql_connection
    
    run_migration "$file"
fi
