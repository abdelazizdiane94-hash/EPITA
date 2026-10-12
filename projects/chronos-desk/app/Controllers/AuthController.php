<?php
class AuthController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Fonction pour enregistrer un nouvel utilisateur
    public function register($username, $password) {
        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Préparation de la requête d'insertion
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->db->prepare($query);
        
        // Liaison des paramètres
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        
        // Exécution de la requête
        if ($stmt->execute()) {
            return true; // Enregistrement réussi
        }
        return false; // Échec de l'enregistrement
    }

    // Fonction pour connecter un utilisateur
    public function login($username, $password) {
        // Préparation de la requête de sélection
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        // Vérification de l'utilisateur
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Vérification du mot de passe
            if (password_verify($password, $user['password'])) {
                // Authentification réussie
                return $user; // Retourne les informations de l'utilisateur
            }
        }
        return false; // Échec de la connexion
    }

    // Fonction pour déconnecter un utilisateur
    public function logout() {
        // Détruire la session de l'utilisateur
        session_start();
        session_unset();
        session_destroy();
    }
}
?>