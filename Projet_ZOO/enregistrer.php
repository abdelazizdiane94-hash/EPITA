<?php
/** Enregistre un compte de personnel depuis le formulaire d'inscription. */
include("connexion.php");

if (isset($_POST["nom"])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $date_naiss = $_POST["date_naissance"];
    $login = $_POST["login"];
    $mdp = $_POST["mdp"];
    $fonction = $_POST["fonction"];

    $reql = "INSERT INTO personnel (nom, prenom, date_de_naissance, login, password, fonction) 
             VALUES ('$nom', '$prenom', '$date_naiss', '$login', '$mdp', '$fonction')";

    $rl = mysqli_query($conn, $reql);

    if ($rl) {
        echo "<center><h3 style='color: green;'>Inscription réussie !</h3>";
        echo "<a href='index.php'>Cliquez ici pour vous connecter</a></center>";
    } else {
        echo "<center><p style='color: red;'>Erreur lors de l'inscription : " . mysqli_error($conn) . "</p></center>";
    }
} else {
    header("location:enregistrer.html");
}

mysqli_close($conn);
?>