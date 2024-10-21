<?php
require_once __DIR__ . '/../config/config.php';

// ... (existing code)

function db_connect() {
    static $conn;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            log_error("Database connection failed: " . $conn->connect_error, 'ERROR');
            die("Connection failed: " . $conn->connect_error);
        }
    }
    return $conn;
}

// ... (rest of the existing code)
?>