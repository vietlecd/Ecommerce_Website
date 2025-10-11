<?php
// Database configuration for Docker environment
return [
    'host' => getenv('DB_HOST') ?: 'mysql',
    'dbname' => getenv('DB_NAME') ?: 'shoe',
    'username' => getenv('DB_USER') ?: 'shoes_user',
    'password' => getenv('DB_PASS') ?: 'shoes_pass',
    'port' => getenv('DB_PORT') ?: '3306'
];
