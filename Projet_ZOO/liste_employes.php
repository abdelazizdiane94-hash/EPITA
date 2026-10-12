<?php
/** Affiche la liste du personnel et les informations RH associées. */
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des employés - Zoo de Milo</title>
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
            max-width: 900px;
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
<h1>Liste des Employés</h1>
            
            <table>
                <thead>
                    <tr>
                        <th>Photo</th> <th>Nom</th>
                        <th>Prénom</th>
                        <th>Fonction</th>
                        <th>Login</th>
                        <th>Salaire (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $requete = "SELECT nom, prenom, fonction, login, salaire, photo 
                                FROM personnel 
                                ORDER BY fonction ASC, nom ASC";
                    
                    $resultat = mysqli_query($conn, $requete);

                    if (mysqli_num_rows($resultat) > 0) {
                        
                        while ($ligne = mysqli_fetch_array($resultat)) {
                            echo "<tr>";
                            
                            echo "<td>";
                            if (!empty($ligne['photo'])) {
                                echo "<img src='" . $ligne['photo'] . "' alt='Photo de " . $ligne['prenom'] . "' style='width: 60px; height: 60px; object-fit: cover; border-radius: 50%; border: 2px solid var(--vert-milo);'>";
                            } else {
                                echo "<span style='color: #888; font-size: 0.8em; font-style: italic;'>Pas de photo</span>";
                            }
                            echo "</td>";

                            echo "<td><strong>" . $ligne['nom'] . "</strong></td>";
                            echo "<td>" . $ligne['prenom'] . "</td>";
                            
                            if ($ligne['fonction'] != "") {
                                echo "<td>" . $ligne['fonction'] . "</td>";
                            } else {
                                echo "<td><em>Non définie</em></td>";
                            }
                            
                            echo "<td>" . $ligne['login'] . "</td>";
                            
                            if ($ligne['salaire'] != "") {
                                echo "<td>" . $ligne['salaire'] . "</td>";
                            } else {
                                echo "<td><em>Non défini</em></td>";
                            }
                            
                            echo "</tr>";
                        }
                        
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center;'>Aucun employé enregistré pour le moment.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>