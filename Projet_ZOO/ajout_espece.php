<?php
/** Ajoute une espèce et l'associe à un type de nourriture éventuel. */
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

$message = "";
if (isset($_POST["nom_espece"])) {
    $nom_espece = $_POST["nom_espece"];
    
    $duree_vie = !empty($_POST["duree_vie_moyenne"]) ? $_POST["duree_vie_moyenne"] : 'NULL';
    
    $aquatique = $_POST["aquatique"];
    
    $id_nourriture = !empty($_POST["id_nourriture"]) ? $_POST["id_nourriture"] : 'NULL';

    $req = "INSERT INTO especes (nom_espece, duree_vie_moyenne, aquatique, id_nourriture) 
            VALUES ('$nom_espece', $duree_vie, $aquatique, $id_nourriture)";

    if (mysqli_query($conn, $req)) {
        $message = "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb;'>L'espèce <b>$nom_espece</b> a été ajoutée avec succès !</div>";
    } else {
        $message = "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb;'>Erreur lors de l'ajout : " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une espèce - Zoo de Milo</title>
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
            align-items: center;
            padding: 40px 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: var(--ombre-douce);
            width: 100%;
            max-width: 500px;
            border-top: 5px solid var(--vert-milo);
        }

        h1 {
            color: var(--vert-milo);
            text-align: center;
            margin-top: 0;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"], input[type="date"], input[type="number"], select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
        }

        .btn-groupe {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            text-decoration: none;
            text-align: center;
            width: 48%;
            box-sizing: border-box;
        }

        .btn-submit { background: var(--vert-milo); }
        .btn-submit:hover { background: var(--vert-milo-clair); }
        
        .btn-retour { background: var(--terre-cuite); }
        .btn-retour:hover { background: #8a4626; }
    </style>
</head>
<body>

    <header>
        <img src="zoo_de_milo.png" alt="Logo Zoo de Milo" class="logo-header">
        <h1 class="header-title">Parc Zoologique de Milo</h1>
    </header>

    <div class="main-content">
        <div class="container">
            <h1>Ajouter une espèce</h1>
            
            <?php echo $message; ?>

            <form action="ajout_espece.php" method="post">
                
                <label>Nom de l'espèce :</label>
                <input type="text" name="nom_espece" required placeholder="Ex: Lion d'Afrique">

                <label>Durée de vie moyenne (en années) :</label>
                <input type="number" name="duree_vie_moyenne" placeholder="Ex: 15">

                <label>L'animal est-il aquatique ?</label>
                <select name="aquatique" required>
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                </select>

                <label>Type de nourriture :</label>
                <select name="id_nourriture">
                    <option value="">-- Choisir une nourriture (Optionnel) --</option>
                    <option value="1">Viandes</option>
                    <option value="2">Poissons</option>
                    <option value="3">Foin et Herbes</option>
                    <option value="4">Fruits et Légumes</option>
                    <option value="5">Insectes</option>
                    <option value="6">Graines et Céréales</option>
                    <option value="7">Granulés complets</option>
                    <option value="8">Nectar</option>

                    <?php
                    $req_nourriture = "SELECT id_nourriture, type_nourriture FROM types_nourriture";
                    $res_nourriture = mysqli_query($conn, $req_nourriture);
                    while($nourriture = mysqli_fetch_array($res_nourriture)) {
                        echo "<option value='".$nourriture['id_nourriture']."'>".$nourriture['type_nourriture']."</option>";
                    }
                    ?>
                </select>

                <div class="btn-groupe">
                    <button type="submit" class="btn btn-submit">Enregistrer</button>
                    <a href="<?php echo $lien_retour; ?>" class="btn btn-retour">Retour au menu</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>