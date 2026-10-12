<?php
/** Ajoute un animal et traite sa photo éventuelle. */
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
if (isset($_POST["nom_animal"]) && isset($_POST["date_de_naissance"]) && isset($_POST["sexe"])) {
    $id_espece = $_POST["id_espece"];
    $nom_animal = $_POST["nom_animal"];
    $date_de_naissance = $_POST["date_de_naissance"];
    $sexe = $_POST["sexe"];
    $commentaire = $_POST["commentaire"]; 

    $id_espece = empty($id_espece) ? 'NULL' : "'$id_espece'";

    $chemin_photo = 'NULL';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $dossier_destination = 'uploads/animaux/';
        
        $infosfichier = pathinfo($_FILES['photo']['name']);
        $extension_upload = strtolower($infosfichier['extension']);
        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png', 'webp');

        if (in_array($extension_upload, $extensions_autorisees)) {
            $nom_image_unique = uniqid() . '_' . basename($_FILES['photo']['name']);
            $chemin_final = $dossier_destination . $nom_image_unique;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $chemin_final)) {
                $chemin_photo = "'$chemin_final'";
            } else {
                $message = "<div style='color: red; text-align: center;'>Erreur lors de l'upload de l'image.</div>";
            }
        } else {
            $message = "<div style='color: red; text-align: center;'>Format d'image non autorisé (Seulement JPG, PNG, GIF, WEBP).</div>";
        }
    }

    $req = "INSERT INTO animaux (id_espece, nom_animal, date_de_naissance, sexe, commentaire, photo) 
            VALUES ($id_espece, '$nom_animal', '$date_de_naissance', '$sexe', '$commentaire', $chemin_photo)";

    if (mysqli_query($conn, $req)) {
        $message = "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb;'>L'animal <b>$nom_animal</b> a été ajouté avec succès !</div>";
    } else {
        $message = "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb;'>Erreur lors de l'ajout : " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un animal - Zoo de Milo</title>
    <style>
        :root {
            --vert-milo: #2d5a27;
            --vert-milo-clair: #3f7d38;
            --ocre-sable: #e0b06b;
            --terre-cuite: #a0522d;
            --blanc-casse: #fdfaf6;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: url('image_zoo_de_milo.png') center/cover fixed;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
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

        textarea {
            resize: vertical;
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

    <div class="container">
        <h1>Ajouter un animal</h1>
        <?php echo $message; ?>

        <form action="ajout_animal.php" method="post" enctype="multipart/form-data">
            
            <label>Espèce :</label>
            <select name="id_espece">
                <option value="">-- Choisir une espèce (Optionnel) --</option>
                <?php
                $req_especes = "SELECT id, nom_espece FROM especes";
                $res_especes = mysqli_query($conn, $req_especes);
                while($espece = mysqli_fetch_array($res_especes)) {
                    echo "<option value='".$espece['id']."'>".$espece['nom_espece']."</option>";
                }
                ?>
            </select>

            <label>Nom de l'animal :</label>
            <input type="text" name="nom_animal" required placeholder="Ex: Simba">

            <label>Date de naissance :</label>
            <input type="date" name="date_de_naissance" required>

            <label>Sexe :</label>
            <select name="sexe" required>
                <option value="">-- Choisir --</option>
                <option value="Mâle">Mâle</option>
                <option value="Femelle">Femelle</option>
                <option value="Inconnu">Inconnu</option>
            </select>

            <label>Photo de l'animal :</label>
            <input type="file" name="photo" accept="image/*">

            <label>Commentaire :</label>
            <textarea name="commentaire" rows="3" placeholder="Notes sur la santé, le comportement, l'origine..."></textarea>

            <div class="btn-groupe">
                <button type="submit" class="btn btn-submit">Enregistrer</button>
                <a href="<?php echo $lien_retour; ?>" class="btn btn-retour">Retour au menu</a>
            </div>
        </form>
    </div>

</body>
</html>