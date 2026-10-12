<?php
$host = getenv('STOCK_DB_HOST') ?: '127.0.0.1';
$name = getenv('STOCK_DB_NAME') ?: 'stock_pulse';
$user = getenv('STOCK_DB_USER') ?: 'root';
$password = getenv('STOCK_DB_PASSWORD') ?: '';
$pdo = new PDO(
    "mysql:host={$host};dbname={$name};charset=utf8mb4",
    $user,
    $password,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]
);