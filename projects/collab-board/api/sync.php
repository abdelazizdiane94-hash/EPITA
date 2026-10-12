<?php
// sync.php
// Ce fichier gère la synchronisation des états du tableau avec la base de données.

// Inclusion du fichier de configuration de la base de données
require_once '../config/db.php';

// Fonction pour synchroniser l'état du tableau
function syncBoardState($boardData) {
    global $pdo;

    // Suppression des anciennes entrées
    $stmt = $pdo->prepare("DELETE FROM cards");
    $stmt->execute();

    // Insertion des nouvelles entrées
    foreach ($boardData as $card) {
        $stmt = $pdo->prepare("INSERT INTO cards (id, title, description, status) VALUES (:id, :title, :description, :status)");
        $stmt->bindParam(':id', $card['id']);
        $stmt->bindParam(':title', $card['title']);
        $stmt->bindParam(':description', $card['description']);
        $stmt->bindParam(':status', $card['status']);
        $stmt->execute();
    }

    return ['status' => 'success', 'message' => 'Board state synchronized successfully.'];
}

// Vérification de la méthode de requête
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du tableau depuis la requête
    $boardData = json_decode(file_get_contents('php://input'), true);

    // Appel de la fonction de synchronisation
    $response = syncBoardState($boardData);

    // Retourner la réponse en JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Méthode non autorisée
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
}
?>