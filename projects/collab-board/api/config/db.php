<?php
$host = getenv('COLLAB_DB_HOST') ?: '127.0.0.1';
$name = getenv('COLLAB_DB_NAME') ?: 'collab_board';
$user = getenv('COLLAB_DB_USER') ?: 'root';
$password = getenv('COLLAB_DB_PASSWORD') ?: '';
$pdo = new PDO(
    "mysql:host={$host};dbname={$name};charset=utf8mb4",
    $user,
    $password,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]
);