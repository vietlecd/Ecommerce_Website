#!/usr/bin/env php
<?php
/**
 * Database Migration Tool
 * 
 * This script handles running SQL migration files in order.
 * 
 * Usage:
 *   php bin/migrate.php [all|filename.sql]
 */

// Load environment and database configuration
require_once __DIR__ . '/../config/environment.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Database.php';

// Set up constants
define('MIGRATIONS_DIR', __DIR__ . '/../assets/config/mysql/migrations');

/**
 * Display usage information
 */
function showUsage() {
    echo "Database Migration Tool\n";
    echo "====================\n\n";
    echo "Usage:\n";
    echo "  php bin/migrate.php [command] [options]\n\n";
    echo "Commands:\n";
    echo "  all                         Run all migrations\n";
    echo "  <filename.sql>              Run a specific migration file\n";
    echo "  rollback [n]                Roll back the last n migrations (default: 1)\n";
    echo "  reset                       Roll back all migrations\n";
    echo "  --help, -h                  Display this help message\n";
    echo "  --list, -l                  List available migrations\n";
    echo "  --status, -s                Show migration status\n\n";
    echo "Examples:\n";
    echo "  php bin/migrate.php all                    # Run all migrations\n";
    echo "  php bin/migrate.php 001_create_qna_tables.sql  # Run a specific migration\n";
    echo "  php bin/migrate.php rollback               # Roll back the last migration\n";
    echo "  php bin/migrate.php rollback 3             # Roll back the last 3 migrations\n";
    echo "  php bin/migrate.php --list                 # List available migrations\n";
    exit(1);
}

/**
 * List all available migrations
 * 
 * @return void
 */
function listMigrations() {
    $files = glob(MIGRATIONS_DIR . '/*.sql');
    sort($files);
    
    if (empty($files)) {
        echo "No migration files found in " . MIGRATIONS_DIR . "\n";
        return;
    }
    
    echo "Available migrations:\n";
    echo "--------------------\n";
    
    foreach ($files as $i => $file) {
        $filename = basename($file);
        $filesize = filesize($file);
        $filedate = date("Y-m-d H:i:s", filemtime($file));
        echo sprintf("%d. %s (%d bytes, modified: %s)\n", $i + 1, $filename, $filesize, $filedate);
    }
}

/**
 * Initialize migrations table if it doesn't exist
 * 
 * @param PDO $pdo Database connection
 * @return bool Success status
 */
function initMigrationsTable(PDO $pdo) {
    try {
        // Check if migrations table exists
        $stmt = $pdo->query("SHOW TABLES LIKE 'migrations'");
        if ($stmt->rowCount() == 0) {
            // Create migrations table
            $sql = file_get_contents(MIGRATIONS_DIR . '/000_create_migrations_table.sql');
            if (!$sql) {
                echo "Error: Could not read migrations table creation file\n";
                return false;
            }
            
            $pdo->exec($sql);
            echo "✅ Migrations tracking table created\n";
        }
        return true;
    } catch (Exception $e) {
        echo "❌ Failed to initialize migrations table: " . $e->getMessage() . "\n";
        return false;
    }
}

/**
 * Check if migration has been applied
 * 
 * @param PDO $pdo Database connection
 * @param string $migration Migration filename
 * @return bool Whether migration has been applied
 */
function isMigrationApplied(PDO $pdo, $migration) {
    $stmt = $pdo->prepare("SELECT * FROM migrations WHERE migration = ?");
    $stmt->execute([$migration]);
    return $stmt->rowCount() > 0;
}

/**
 * Record that a migration has been applied
 * 
 * @param PDO $pdo Database connection
 * @param string $migration Migration filename
 * @param int $batch Batch number
 * @return bool Success status
 */
function recordMigration(PDO $pdo, $migration, $batch) {
    try {
        $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
        return true;
    } catch (Exception $e) {
        echo "❌ Failed to record migration: " . $e->getMessage() . "\n";
        return false;
    }
}

/**
 * Get the next batch number
 * 
 * @param PDO $pdo Database connection
 * @return int Next batch number
 */
function getNextBatchNumber(PDO $pdo) {
    $stmt = $pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
    $result = $stmt->fetch();
    return ($result && $result['max_batch']) ? $result['max_batch'] + 1 : 1;
}

/**
 * Show migration status
 * 
 * @param PDO $pdo Database connection
 */
function showMigrationStatus(PDO $pdo) {
    // Get all migration files
    $files = glob(MIGRATIONS_DIR . '/*.sql');
    sort($files);
    
    if (empty($files)) {
        echo "No migration files found in " . MIGRATIONS_DIR . "\n";
        return;
    }
    
    echo "Migration Status:\n";
    echo "----------------------------------------\n";
    echo str_pad("Migration", 40) . str_pad("Status", 15) . "Applied At\n";
    echo "----------------------------------------\n";
    
    // Filter out down files for cleaner status output
    $displayFiles = [];
    foreach ($files as $file) {
        if (strpos(basename($file), '.down.sql') === false) {
            $displayFiles[] = $file;
        }
    }
    $files = $displayFiles;
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        // Check if migration has been applied
        $stmt = $pdo->prepare("SELECT applied_at FROM migrations WHERE migration = ?");
        $stmt->execute([$filename]);
        $result = $stmt->fetch();
        
        if ($result) {
            echo str_pad($filename, 40) . str_pad("✅ Applied", 15) . $result['applied_at'] . "\n";
        } else {
            echo str_pad($filename, 40) . "❌ Pending\n";
        }
    }
}

/**
 * Run a single migration file
 * 
 * @param PDO $pdo Database connection
 * @param string $filePath Path to the migration file
 * @param int $batch Batch number
 * @return bool Success status
 */
function runMigration(PDO $pdo, $filePath, $batch = null) {
    $filename = basename($filePath);
    
    // Skip rollback files
    if (strpos($filename, '.down.sql') !== false) {
        echo "⏭️ Skipping rollback file: $filename\n";
        return true;
    }
    
    // Skip migrations table creation
    if ($filename === '000_create_migrations_table.sql') {
        echo "⏭️ Skipping migrations table creation file\n";
        return true;
    }
    
    // Skip if already applied
    if (isMigrationApplied($pdo, $filename)) {
        echo "⏭️ Migration $filename already applied, skipping\n";
        return true;
    }
    
    echo "Running migration: $filename\n";
    
    try {
        // Get migration content
        $sql = file_get_contents($filePath);
        if (!$sql) {
            echo "Error: Could not read file $filePath\n";
            return false;
        }
        
        // Execute the migration
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $pdo->beginTransaction();
        
        $result = $pdo->exec($sql);
        if ($result === false) {
            $error = $pdo->errorInfo();
            throw new Exception("Database error: " . $error[2]);
        }
        
        // Record the migration
        if ($batch === null) {
            $batch = getNextBatchNumber($pdo);
        }
        
        $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$filename, $batch]);
        
        // Commit transaction
        $pdo->commit();
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        
        echo "✅ Migration $filename completed successfully\n";
        return true;
    } catch (Exception $e) {
        // Rollback transaction
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        
        echo "❌ Migration failed: " . $e->getMessage() . "\n";
        return false;
    }
}

/**
 * Roll back migrations
 * 
 * @param PDO $pdo Database connection
 * @param int|string $steps Number of batches to roll back or 'all'
 * @return bool Success status
 */
function rollbackMigrations(PDO $pdo, $steps = 1) {
    try {
        if ($steps === 'all') {
            // Get all migrations
            $stmt = $pdo->query("SELECT * FROM migrations ORDER BY batch DESC, id DESC");
        } else {
            // Get the last batch
            $latestBatch = $pdo->query("SELECT MAX(batch) as max_batch FROM migrations")->fetch()['max_batch'];
            
            // Get migrations from the latest batch
            $stmt = $pdo->prepare("SELECT * FROM migrations WHERE batch = ? ORDER BY id DESC");
            $stmt->execute([$latestBatch]);
        }
        
        $migrations = $stmt->fetchAll();
        
        if (empty($migrations)) {
            echo "No migrations to roll back\n";
            return true;
        }
        
        echo "Rolling back " . ($steps == 'all' ? "all migrations" : "the last $steps batch(es)") . "\n";
        
        foreach ($migrations as $migration) {
            echo "Rolling back: " . $migration['migration'] . "\n";
            
            // Generate rollback name (look for matching .down.sql file)
            $rollbackFile = MIGRATIONS_DIR . '/' . pathinfo($migration['migration'], PATHINFO_FILENAME) . '.down.sql';
            
            if (!file_exists($rollbackFile)) {
                echo "❌ No rollback file found for " . $migration['migration'] . "\n";
                echo "Expected file: " . $rollbackFile . "\n";
                continue;
            }
            
            try {
                // Get rollback SQL
                $sql = file_get_contents($rollbackFile);
                if (!$sql) {
                    echo "Error: Could not read rollback file $rollbackFile\n";
                    continue;
                }
                
                // Execute rollback SQL with transaction handling
                $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
                $pdo->beginTransaction();
                
                $result = $pdo->exec($sql);
                
                // Remove from migrations table
                $stmt = $pdo->prepare("DELETE FROM migrations WHERE id = ?");
                $stmt->execute([$migration['id']]);
                
                // Commit transaction
                $pdo->commit();
                $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
                
                echo "✅ Rolled back: " . $migration['migration'] . "\n";
            } catch (Exception $e) {
                // Rollback transaction
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
                
                echo "❌ Failed to roll back " . $migration['migration'] . ": " . $e->getMessage() . "\n";
                continue; // Continue with next migration instead of failing
            }
        }
        
        echo "✅ Rollback complete\n";
        return true;
    } catch (Exception $e) {
        echo "❌ Rollback failed: " . $e->getMessage() . "\n";
        return false;
    }
}

// Main execution
try {
    // Check for help command first, before trying to connect to the database
    if ($argc < 2 || in_array($argv[1], ['--help', '-h'])) {
        showUsage();
    }
    
    $command = isset($argv[1]) ? $argv[1] : '';
    
    // These commands don't need database access
    $noDatabaseCommands = ['--help', '-h', '--list', '-l'];
    
    // Connect to database if needed
    $pdo = null;
    if (!in_array($command, $noDatabaseCommands)) {
        echo "Connecting to database...\n";
        
        // Load environment variables if they're not already set
        if (!isset($_ENV['DB_HOST'])) {
            $_ENV['DB_HOST'] = 'mysql';
            $_ENV['DB_NAME'] = 'shoe';
            $_ENV['DB_USER'] = 'shoes_user';
            $_ENV['DB_PASS'] = 'shoes_pass';
        }
        
        $db = new Database();
        $pdo = $db->getConnection();
        echo "Database connection established.\n";
    }
    
    // First, create the migrations table if needed
    if ($pdo !== null) {
        initMigrationsTable($pdo);
    }
    
    // Handle commands
    switch ($command) {
        case '--list':
        case '-l':
            listMigrations();
            break;
            
        case '--status':
        case '-s':
            if ($pdo === null) {
                echo "Error: Database connection required for --status\n";
                exit(1);
            }
            showMigrationStatus($pdo);
            break;
            
        case 'rollback':
            echo "Rolling back migrations\n";
            echo "====================\n\n";
            
            if ($pdo === null) {
                echo "Error: Database connection required for rollback\n";
                exit(1);
            }
            
            initMigrationsTable($pdo);
            
            // Check if a step parameter was provided
            $steps = isset($argv[2]) ? $argv[2] : 1;
            
            if (!is_numeric($steps) && $steps !== 'all') {
                echo "Error: Steps must be a number or 'all'\n";
                exit(1);
            }
            
            $success = rollbackMigrations($pdo, $steps);
            exit($success ? 0 : 1);
            
        case 'reset':
            echo "Resetting all migrations\n";
            echo "====================\n\n";
            
            if ($pdo === null) {
                echo "Error: Database connection required for reset\n";
                exit(1);
            }
            
            initMigrationsTable($pdo);
            $success = rollbackMigrations($pdo, 'all');
            exit($success ? 0 : 1);
            
        case 'all':
            echo "Running all migrations in order\n";
            echo "==============================\n\n";
            
            // Initialize migrations table
            if (!initMigrationsTable($pdo)) {
                exit(1);
            }
            
            // Get all SQL files and sort them
            $files = glob(MIGRATIONS_DIR . '/*.sql');
            sort($files);
            
            if (empty($files)) {
                echo "No migration files found in " . MIGRATIONS_DIR . "\n";
                exit(0);
            }
            
            $successCount = 0;
            $failCount = 0;
            $batch = getNextBatchNumber($pdo);
            
            // Run each migration
            foreach ($files as $file) {
                if (basename($file) === '000_create_migrations_table.sql') {
                    continue; // Skip the migrations table creation file
                }
                
                if (runMigration($pdo, $file, $batch)) {
                    $successCount++;
                } else {
                    $failCount++;
                    // We continue despite failures to show all errors
                }
            }
            
            echo "\n==============================\n";
            echo "Migration Summary:\n";
            echo "  ✅ Successful: $successCount\n";
            echo "  ❌ Failed: $failCount\n";
            
            if ($failCount > 0) {
                echo "\nSome migrations failed. Check the errors above.\n";
                exit(1);
            }
            break;
            
        default:
            // Assume it's a migration file
            if (substr($command, 0, 2) === '--') {
                echo "Unknown option: $command\n\n";
                showUsage();
            }
            
            // Run a specific migration file
            $filePath = MIGRATIONS_DIR . '/' . $command;
            
            if (!file_exists($filePath)) {
                echo "Error: Migration file not found: $filePath\n";
                echo "Run with --list to see available migrations or --help for usage information.\n";
                exit(1);
            }
            
            $success = runMigration($pdo, $filePath);
            exit($success ? 0 : 1);
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
