<?php
/** Connexion MySQL locale partagée par les scripts du Zoo de Milo. */
$conn = mysqli_connect('localhost', 'root', 'mysql', 'gestionzoo');
if (!$conn) {
	http_response_code(503);
	exit('Connexion à la base de données impossible. Vérifiez la configuration locale.');
}
mysqli_set_charset($conn, 'utf8mb4');
?>