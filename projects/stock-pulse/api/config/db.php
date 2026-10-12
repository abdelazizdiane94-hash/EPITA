<?php
// Configuration file for the StockPulse API database connection

// Database credentials
define('DB_HOST', 'localhost'); // Database host
define('DB_NAME', 'stock_pulse'); // Database name
define('DB_USER', 'root'); // Database username
define('DB_PASS', ''); // Database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
}
?>