<?php
// Database configuration for Docker environment
return [
    'host' => $_ENV['DB_HOST'] ?? 'mysql',
    'dbname' => $_ENV['DB_NAME'] ?? 'shoe',
    'username' => $_ENV['DB_USER'] ?? 'shoes_user',
    'password' => $_ENV['DB_PASS'] ?? 'shoes_pass',
    'port' => $_ENV['DB_PORT'] ?? '3306'
];
