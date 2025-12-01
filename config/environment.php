<?php
// Environment configuration
// Copy this file and modify for your environment

return [
    'development' => [
        'DB_HOST' => 'mysql',
        'DB_NAME' => 'shoe',
        'DB_USER' => 'shoes_user',
        'DB_PASS' => 'shoes_pass',
        'DB_PORT' => '3306',
        'APP_DEBUG' => true
    ],
    'production' => [
        'DB_HOST' => 'localhost',
        'DB_NAME' => 'shoe',
        'DB_USER' => 'your_prod_user',
        'DB_PASS' => 'your_prod_password',
        'DB_PORT' => '3306',
        'APP_DEBUG' => false
        
    ]
];
