<?php
/** Ajoute un membre du personnel et traite sa photo éventuelle. */
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
if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["login"]) && isset($_POST["password"])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $date_de_naissance = !empty($_POST["date_de_naissance"]) ? "'".$_POST["date_de_naissance"]."'" : 'NULL';
    $login = $_POST["login"];
    $password = $_POST["password"];
    $salaire = !empty($_POST["salaire"]) ? $_POST["salaire"] : 'NULL';
    $sexe = !empty($_POST["sexe"]) ? "'".$_POST["sexe"]."'" : 'NULL';
    $fonction = !empty($_POST["fonction"]) ? "'".$_POST["fonction"]."'" : 'NULL';

    $chemin_photo = 'NULL';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $dossier_destination = 'uploads/personnel/';
        
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

    $req = "INSERT INTO personnel (nom, prenom, date_de_naissance, login, password, salaire, sexe, fonction, photo) 
            VALUES ('$nom', '$prenom', $date_de_naissance, '$login', '$password', $salaire, $sexe, $fonction, $chemin_photo)";

    if (mysqli_query($conn, $req)) {
        $message = "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb;'>L'employé(e) <b>$prenom $nom</b> a été ajouté(e) avec succès !</div>";
    } else {
        $message = "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb;'>Erreur lors de l'ajout : " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un employé - Zoo de Milo</title>
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

        input[type="text"], input[type="password"], input[type="date"], input[type="number"], select, textarea {
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
            <h1>Ajouter un employé</h1>
            
            <?php echo $message; ?>

            <form action="ajout_employe.php" method="post" enctype="multipart/form-data">
                
                <label>Nom :</label>
                <input type="text" name="nom" required placeholder="Ex: Dupont">

                <label>Prénom :</label>
                <input type="text" name="prenom" required placeholder="Ex: Jean">

                <label>Sexe :</label>
                <select name="sexe">
                    <option value="">-- Choisir --</option>
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                    <option value="Autre">Autre</option>
                </select>

                <label>Date de naissance :</label>
                <input type="date" name="date_de_naissance">

                <label>Photo de profil :</label>
                <input type="file" name="photo" accept="image/*">

                <label>Fonction :</label>
                <select name="fonction" required>
                    <option value="">-- Choisir un rôle --</option>
                    <option value="Employé">Employé</option>
                    <option value="Directeur">Directeur</option>
                </select>

                <label>Salaire mensuel (€) :</label>
                <input type="number" step="0.01" name="salaire" placeholder="Ex: 2100.50">

                <hr style="margin-top: 25px; border: 0; border-top: 1px solid #ccc;">
                <h3 style="color: var(--vert-milo); margin-bottom: 0;">Identifiants de connexion</h3>

                <label>Login :</label>
                <input type="text" name="login" required placeholder="Ex: jdupont">

                <label>Mot de passe :</label>
                <input type="password" name="password" required>

                <div class="btn-groupe">
                    <button type="submit" class="btn btn-submit">Enregistrer</button>
                    <a href="<?php echo $lien_retour; ?>" class="btn btn-retour">Retour au menu</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>