<?php
class AuthController {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }

    public function register($username, $password) {
        $username = trim((string) $username);
        if ($username === '' || strlen($username) > 50 || strlen((string) $password) < 12) {
            return false;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare('INSERT INTO users (username, password, email, role_id) VALUES (:username, :password, :email, (SELECT id FROM roles WHERE role_name = :role))');
            return $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
                ':email' => strtolower($username) . '@example.invalid',
                ':role' => 'Collaborateur'
            ]);
        } catch (PDOException $exception) {
            return false;
        }
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare('SELECT users.id, users.username, users.password, roles.role_name AS role FROM users JOIN roles ON roles.id = users.role_id WHERE users.username = :username LIMIT 1');
        $stmt->execute([':username' => trim((string) $username)]);
        $user = $stmt->fetch();
        if ($user && password_verify((string) $password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    public function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }
}