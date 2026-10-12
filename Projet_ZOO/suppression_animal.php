<?php
/** Supprime un animal après validation du rôle Directeur et de l'identifiant. */
session_start();
if (($_SESSION['role'] ?? null) !== 'Directeur') {
	http_response_code(403);
	exit('Accès réservé au Directeur.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	header('Allow: POST');
	exit('Méthode non autorisée.');
}

$animalId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$animalId || $animalId < 1) {
	http_response_code(400);
	exit('Identifiant animal invalide.');
}

require __DIR__ . '/connexion.php';
$statement = mysqli_prepare($conn, 'DELETE FROM animaux WHERE id = ?');
mysqli_stmt_bind_param($statement, 'i', $animalId);
$success = mysqli_stmt_execute($statement);
mysqli_stmt_close($statement);
mysqli_close($conn);

header('Location: liste_animaux.php?deleted=' . ($success ? '1' : '0'));
exit;
