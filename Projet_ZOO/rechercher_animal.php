<?php
/** Recherche des animaux par nom ou espèce. */
session_start();
include("connexion.php");

// 1. Gestion du bouton de retour
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
    <title>Rechercher un animal - Zoo de Milo</title>
    <style>
        /* Les couleurs de ton zoo ! */
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
            margin-bottom: 20px;
        }

        /* La barre de recherche */
        .form-recherche {
            text-align: center;
            margin-bottom: 30px;
            background: #f1f8f0;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #c3e6cb;
        }

        input[type="text"] {
            width: 60%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-family: inherit;
            margin-right: 10px;
        }

        /* Le tableau */
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

        /* Les boutons */
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

        .btn-submit { background: var(--vert-milo); }
        .btn-submit:hover { background: var(--vert-milo-clair); }

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
            <h1>Rechercher un Animal</h1>
            
            <div class="form-recherche">
                <form action="rechercher_animal.php" method="post">
                    <input type="text" name="mot_cle" placeholder="Tapez un nom d'animal ou une espèce..." required>
                    <button type="submit" class="btn btn-submit">Rechercher</button>
                </form>
            </div>

            <?php
            // On vérifie si une recherche a été lancée
            if (isset($_POST['mot_cle'])) {
                $recherche = $_POST['mot_cle'];
                
                echo "<h3 style='color: #333;'>Résultats pour : <i>" . htmlspecialchars($recherche) . "</i></h3>";

                // 2. La requête magique : on lie les deux tables et on cherche dans les deux colonnes !
                $requete = "SELECT animaux.nom_animal, especes.nom_espece, animaux.sexe, animaux.date_de_naissance, animaux.commentaire 
                            FROM animaux 
                            LEFT JOIN especes ON animaux.id_espece = especes.id 
                            WHERE animaux.nom_animal LIKE '%$recherche%' OR especes.nom_espece LIKE '%$recherche%' 
                            ORDER BY animaux.nom_animal ASC";
                
                $resultat = mysqli_query($conn, $requete);

                // 3. On affiche le tableau s'il y a des résultats
                if (mysqli_num_rows($resultat) > 0) {
                    echo "<table>";
                    echo "<thead>
                            <tr>
                                <th>Nom de l'animal</th>
                                <th>Espèce</th>
                                <th>Sexe</th>
                                <th>Date de naissance</th>
                                <th>Commentaire</th>
                            </tr>
                          </thead>";
                    echo "<tbody>";
                    
                    // 4. On lit et on affiche
                    while ($ligne = mysqli_fetch_array($resultat)) {
                        echo "<tr>";
                        echo "<td><strong>" . $ligne['nom_animal'] . "</strong></td>";
                        
                        if ($ligne['nom_espece'] != "") {
                            echo "<td>" . $ligne['nom_espece'] . "</td>";
                        } else {
                            echo "<td><em>Inconnue</em></td>";
                        }
                        
                        echo "<td>" . $ligne['sexe'] . "</td>";
                        echo "<td>" . $ligne['date_de_naissance'] . "</td>";
                        echo "<td>" . $ligne['commentaire'] . "</td>";
                        echo "</tr>";
                    }
                    
                    echo "</tbody></table>";
                    
                } else {
                    // Si on ne trouve ni ce nom, ni cette espèce
                    echo "<p style='text-align: center; color: red;'>Aucun animal ni aucune espèce ne correspond à cette recherche.</p>";
                }
            }
            ?>

            <div class="center-btn">
                <a href="<?php echo $lien_retour; ?>" class="btn btn-retour">Retour au menu</a>
            </div>
        </div>
    </div>

</body>
</html>