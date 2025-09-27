<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $pdo;

    public function __construct()
    {
        // Load configuration from environment variables or defaults
        $this->host = $_ENV['DB_HOST'] ?? 'mysql';
        $this->db_name = $_ENV['DB_NAME'] ?? 'shoe';
        $this->username = $_ENV['DB_USER'] ?? 'shoes_user';
        $this->password = $_ENV['DB_PASS'] ?? 'shoes_pass';

        // Retry connection with backoff
        $maxRetries = 5;
        $retryDelay = 2; // seconds

        for ($i = 0; $i < $maxRetries; $i++) {
            try {
                $this->pdo = new PDO(
                    "mysql:host=$this->host;dbname=$this->db_name;charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
                // Connection successful, break out of retry loop
                break;
            } catch (PDOException $e) {
                if ($i === $maxRetries - 1) {
                    // Last attempt failed
                    error_log("Database connection failed after $maxRetries attempts: " . $e->getMessage());
                    die("Database connection failed. Please check if MySQL is running and accessible.");
                }
                // Wait before retrying
                sleep($retryDelay);
                $retryDelay *= 2; // Exponential backoff
            }
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
