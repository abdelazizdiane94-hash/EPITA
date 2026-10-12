<?php
/** Authentifie un membre du personnel et redirige selon son rôle. */
session_start();
include("connexion.php");

if (isset($_POST["login"]) && isset($_POST["password"])) {
    $login = $_POST["login"]; 
    $password = $_POST["password"];

    $requete = "SELECT * FROM personnel WHERE login = ? AND password = ?";   
    $stmt = mysqli_prepare($conn, $requete);
    mysqli_stmt_bind_param($stmt, "ss", $login, $password);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);
    
    if ($enreg = mysqli_fetch_array($resultat)) {
        $_SESSION['role'] = $enreg['fonction'];

        if ($enreg['fonction'] == 'Directeur') {
            header("Location: menu_directeur.php");
            exit();
        } else {
            header("Location: menu_employe.php");
            exit();
        }
    } else {
        echo "<center><p style='color: red;'>Login ou mot de passe incorrect.</p>";
        echo "<a href='index.php'>Réessayer</a></center>";
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>