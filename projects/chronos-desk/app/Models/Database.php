<?php
class Database {
    private $conn;

    public function getConnection() {
        if ($this->conn instanceof PDO) {
            return $this->conn;
        }

        $host = getenv('CHRONOS_DB_HOST') ?: '127.0.0.1';
        $name = getenv('CHRONOS_DB_NAME') ?: 'chronos_desk';
        $user = getenv('CHRONOS_DB_USER') ?: 'root';
        $password = getenv('CHRONOS_DB_PASSWORD') ?: '';
        $this->conn = new PDO(
            "mysql:host={$host};dbname={$name};charset=utf8mb4",
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]
        );
        return $this->conn;
    }
}
?>