#!/bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
cd "$PROJECT_ROOT"

SEEDERS_DIR="assets/config/mysql/seeders"
DOCKER_SERVICE="mysql"
DB_NAME="shoe"
DB_USER="shoes_user"
DB_PASS="shoes_pass"

echo "====================================="
echo "MySQL Database Seeder"
echo "====================================="

check_mysql_connection() {
    echo "Checking MySQL connection..."
    if ! docker compose exec $DOCKER_SERVICE mysql -u$DB_USER -p$DB_PASS -e "SELECT 1" &>/dev/null; then
        echo "❌ Failed to connect to MySQL. Make sure Docker is running and MySQL service is available."
        exit 1
    else
        echo "✅ MySQL connection successful"
    fi
}

run_seeder() {
    local file=$1
    local full_path="$SEEDERS_DIR/$file"
    
    if [ ! -f "$full_path" ]; then
        echo "Error: Seeder file not found: $full_path"
        echo "Run with --list to see available seeders or --help for usage information."
        exit 1
    fi
    
    echo "Running seeder: $file"
    
    docker compose cp "$full_path" $DOCKER_SERVICE:/tmp/
    docker compose exec $DOCKER_SERVICE mysql -u$DB_USER -p$DB_PASS $DB_NAME -e "SOURCE /tmp/$file"
    
    if [ $? -eq 0 ]; then
        echo "✅ Seeder $file completed"
    else
        echo "❌ Seeder $file failed"
        exit 1
    fi
}

list_seeders() {
    echo "Available seeders:"
    if [ -d "$SEEDERS_DIR" ]; then
        local count=0
        for file in $(find "$SEEDERS_DIR" -name "*.sql" -not -name "*.down.sql" | sort); do
            local filename=$(basename "$file")
            echo "  - $filename"
            count=$((count+1))
        done
        
        if [ $count -eq 0 ]; then
            echo "  No seeder files found in $SEEDERS_DIR"
        fi
    else
        echo "  Directory $SEEDERS_DIR does not exist."
    fi
}

show_help() {
    echo "Usage: ./bin/seed.sh [command] [options]"
    echo "Commands:"
    echo "  all                       Run all seeders"
    echo "  reset                     Remove all seeded data"
    echo "  <filename.sql>            Run a specific seeder file"
    echo "  --help, -h                Display this help message"
    echo "  --list, -l                List available seeders"
    echo "  --clear [all|file.sql]    Clear tables before running seeders"
    echo ""
    echo "Examples:"
    echo "  ./bin/seed.sh all                           # Run all seeders"
    echo "  ./bin/seed.sh 001_seed_about_qna_data.sql   # Run a specific seeder"
    echo "  ./bin/seed.sh reset                         # Clear all seeded data"
    echo "  ./bin/seed.sh --clear all                   # Clear tables before running all seeders"
    echo "  ./bin/seed.sh --clear 001_seed_about_qna_data.sql  # Clear tables before running specific seeder"
    echo "  ./bin/seed.sh --list                        # List available seeders"
}

clear_tables_for_seeder() {
    local file=$1
    
    echo "Analyzing $file to identify tables..."
    tables=$(grep -o -E "INSERT INTO \`([a-zA-Z0-9_]+)\`" "$file" | sed -E "s/INSERT INTO \`([a-zA-Z0-9_]+)\`/\1/" | sort -u)
    
    if [ -z "$tables" ]; then
        echo "⚠️ No tables identified for clearing in $file"
        return 0
    fi
    
    echo "Clearing tables before seeding:"
    for table in $tables; do
        echo "  - Clearing table: $table"
        docker compose exec $DOCKER_SERVICE mysql -u$DB_USER -p$DB_PASS $DB_NAME -e "DELETE FROM \`$table\`; ALTER TABLE \`$table\` AUTO_INCREMENT = 1;"
        if [ $? -eq 0 ]; then
            echo "    ✅ Cleared table $table"
        else
            echo "    ❌ Failed to clear table $table"
            return 1
        fi
    done
    echo "✅ All tables cleared successfully"
    return 0
}

clear_all_tables() {
    echo "Analyzing all seeder files to identify tables..."
    local all_tables=""
    
    for file in $(find "$SEEDERS_DIR" -name "*.sql" -not -name "*.down.sql" | sort); do
        echo "- $(basename "$file")"
        tables=$(grep -o -E "INSERT INTO \`([a-zA-Z0-9_]+)\`" "$file" | sed -E "s/INSERT INTO \`([a-zA-Z0-9_]+)\`/\1/")
        all_tables="$all_tables $tables"
    done
    
    unique_tables=$(echo "$all_tables" | tr ' ' '\n' | sort -u | grep -v "^$")
    
    if [ -z "$unique_tables" ]; then
        echo "⚠️ No tables identified for clearing"
        return 0
    fi
    
    echo "Clearing tables before seeding:"
    for table in $unique_tables; do
        echo "  - Clearing table: $table"
        docker compose exec $DOCKER_SERVICE mysql -u$DB_USER -p$DB_PASS $DB_NAME -e "DELETE FROM \`$table\`; ALTER TABLE \`$table\` AUTO_INCREMENT = 1;"
        if [ $? -eq 0 ]; then
            echo "    ✅ Cleared table $table"
        else
            echo "    ❌ Failed to clear table $table"
            return 1
        fi
    done
    echo "✅ All tables cleared successfully"
    return 0
}

reset_all_seeded_tables() {
    echo "Resetting all seeded tables..."
    echo "Analyzing all seeder files to identify tables..."
    local all_tables=""
    
    for file in $(find "$SEEDERS_DIR" -name "*.sql" -not -name "*.down.sql" | sort); do
        echo "- $(basename "$file")"
        tables=$(grep -o -E "INSERT INTO \`([a-zA-Z0-9_]+)\`" "$file" | sed -E "s/INSERT INTO \`([a-zA-Z0-9_]+)\`/\1/")
        all_tables="$all_tables $tables"
    done
    
    unique_tables=$(echo "$all_tables" | tr ' ' '\n' | sort -u | grep -v "^$")
    
    if [ -z "$unique_tables" ]; then
        echo "⚠️ No tables identified for reset"
        return 0
    fi
    
    echo "Tables identified for reset:"
    for table in $unique_tables; do
        echo "  - $table"
    done
    
    echo "Clearing table data:"
    for table in $unique_tables; do
        echo "  - Clearing table: $table"
        docker compose exec $DOCKER_SERVICE mysql -u$DB_USER -p$DB_PASS $DB_NAME -e "DELETE FROM \`$table\`; ALTER TABLE \`$table\` AUTO_INCREMENT = 1;"
        if [ $? -eq 0 ]; then
            echo "    ✅ Cleared table $table"
        else
            echo "    ❌ Failed to clear table $table"
            return 1
        fi
    done
    echo "✅ All seeded data has been removed"
    return 0
}

case "$1" in
    "all")
        check_mysql_connection
        
        echo "Running all seeders in $SEEDERS_DIR"
        for file in $(find "$SEEDERS_DIR" -name "*.sql" -not -name "*.down.sql" | sort); do
            run_seeder "$(basename "$file")"
        done
        
        echo "====================================="
        echo "✅ All seeders completed successfully"
        echo "====================================="
        ;;
    
    "reset")
        check_mysql_connection
        reset_all_seeded_tables
        ;;
    
    "--list" | "-l")
        list_seeders
        ;;
    
    "--help" | "-h")
        show_help
        ;;
    
    "--clear")
        check_mysql_connection
        case "$2" in
            "all")
                clear_all_tables && 
                echo "Running all seeders in $SEEDERS_DIR"
                for file in $(find "$SEEDERS_DIR" -name "*.sql" -not -name "*.down.sql" | sort); do
                    run_seeder "$(basename "$file")"
                done
                
                echo "====================================="
                echo "✅ All seeders completed successfully"
                echo "====================================="
                ;;
            
            "")
                echo "Error: --clear requires an argument (all or a specific file)"
                show_help
                exit 1
                ;;
            
            *)
                file="$SEEDERS_DIR/$2"
                if [ -f "$file" ]; then
                    clear_tables_for_seeder "$file" && run_seeder "$2"
                else
                    echo "Error: Seeder file not found: $file"
                    list_seeders
                    exit 1
                fi
                ;;
        esac
        ;;
    
    "")
        echo "Error: No command specified"
        show_help
        exit 1
        ;;
    
    *)
        run_seeder "$1"
        ;;
esac

exit 0
