<?php
/** Affiche les animaux et leurs espèces avec les actions du menu courant. */
session_start();
include("connexion.php");
$lien_retour = "index.php";
if (isset($_SESSION['role'])) { 
    if ($_SESSION['role'] == 'Directeur') { 
        $lien_retour = "menu_directeur.php";
    } elseif ($_SESSION['role'] == 'Employé') {
        $lien_retour = "menu_employe.php";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des animaux - Zoo de Milo</title>
    <style>
        :root {
            --vert-milo: #2d5a27;
            --vert-milo-clair: #3f7d38;
            --ocre-sable: #e0b06b;
            --terre-cuite: #a0522d;
            --blanc-casse: #fdfaf6;
            --ombre-douce: 0 10px 30px rgba(0,0,0,0.15);
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: url('image_zoo_de_milo.png') center/cover fixed;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: var(--vert-milo);
            padding: 20px 40px;
            display: flex;
            align-items: center;
            border-bottom: 4px solid var(--ocre-sable);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: relative; 
            z-index: 10;
        }

        .logo-header {
            width: 60px;
            height: 60px;
            margin-right: 20px;
            border-radius: 12px;
            background: white;
            padding: 4px;
        }

        .header-title {
            color: white;
            margin: 0;
            font-size: 1.8rem;
            letter-spacing: 0.5px;
        }

        .main-content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: var(--ombre-douce);
            width: 100%;
            max-width: 1000px;
            border-top: 5px solid var(--vert-milo);
            overflow-x: auto;
        }

        h1 {
            color: var(--vert-milo);
            text-align: center;
            margin-top: 0;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--vert-milo);
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f8f0;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn-retour { background: var(--terre-cuite); }
        .btn-retour:hover { background: #8a4626; }
        
        .btn-action {
            padding: 6px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 0.9em;
            margin-right: 5px;
            display: inline-block;
            margin-bottom: 4px;
        }
        
        .btn-modifier { 
            background-color: var(--ocre-sable); 
            color: #333;
        }
        .btn-modifier:hover { background-color: #c89c5f; }
        
        .btn-supprimer { 
            background-color: #d9534f;
        }
        .btn-supprimer:hover { background-color: #c9302c; }

        .center-btn {
            text-align: center;
        }
    </style>
</head>
<body>

    <header>
        <img src="zoo_de_milo.png" alt="Logo Zoo de Milo" class="logo-header">
        <h1 class="header-title">Parc Zoologique de Milo</h1>
    </header>

    <div class="main-content">
        <div class="container">
            <h1>Liste des Animaux</h1>
            
            <table>
                <thead>
    <tr>
        <th>Photo</th> <th>Nom</th>
        <th>Espèce</th>
        <th>Sexe</th>
        <th>Date de naissance</th>
        <th>Commentaire</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php
    $requete = "SELECT animaux.id, animaux.nom_animal, especes.nom_espece, animaux.sexe, animaux.date_de_naissance, animaux.commentaire, animaux.photo 
                FROM animaux 
                LEFT JOIN especes ON animaux.id_espece = especes.id";
    
    $resultat = mysqli_query($conn, $requete);

    if (mysqli_num_rows($resultat) > 0) {
        
        while ($ligne = mysqli_fetch_array($resultat)) {
            echo "<tr>";
            
            echo "<td>";
            if (!empty($ligne['photo'])) {
                echo "<img src='" . $ligne['photo'] . "' alt='Photo de " . $ligne['nom_animal'] . "' style='width: 80px; height: 80px; object-fit: cover; border-radius: 8px;'>";
            } else {
                echo "<span style='color: #888; font-size: 0.9em;'>Aucune photo</span>";
            }
            echo "</td>";

            echo "<td><strong>" . $ligne['nom_animal'] . "</strong></td>";
            
            if ($ligne['nom_espece'] != "") {
                echo "<td>" . $ligne['nom_espece'] . "</td>";
            } else {
                echo "<td><em>Inconnue</em></td>";
            }
            
            echo "<td>" . $ligne['sexe'] . "</td>";
            echo "<td>" . $ligne['date_de_naissance'] . "</td>";
            
            echo "<td>" . $ligne['commentaire'] . "</td>";
            
            echo "<td>";
            if (($_SESSION['role'] ?? '') === 'Directeur') {
                echo "<form method='post' action='suppression_animal.php' onsubmit='return confirm(\"Supprimer cet animal ?\");'>";
                echo "<input type='hidden' name='id' value='" . (int) $ligne['id'] . "'>";
                echo "<button type='submit' class='btn-action btn-supprimer'>Supprimer</button></form>";
            }
            echo "</td>";
            
            echo "</tr>";
        }
        
    } else {
        echo "<tr><td colspan='7' style='text-align: center;'>Aucun animal dans le zoo pour le moment.</td></tr>";
    }
    ?>
</tbody>