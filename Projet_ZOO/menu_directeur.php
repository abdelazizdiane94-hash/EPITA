<?php /** Menu Directeur : accès aux fonctions d'administration du zoo. */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Directeur - Parc Zoologique de Milo</title>
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
        color: #333;
        line-height: 1.6;
    }

    header {
        background: var(--vert-milo);
        padding: 20px 40px;
        display: flex;
        align-items: center;
        border-bottom: 4px solid var(--ocre-sable);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
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

    .container {
        max-width: 800px;
        margin: 50px auto;
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 20px;
        box-shadow: var(--ombre-douce);
        transition: transform 0.4s ease;
    }

    .container:hover {
        transform: translateY(-5px);
    }

    .menu-section {
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .btn {
        display: inline-block;
        background: var(--vert-milo);
        color: white;
        padding: 12px 24px;
        text-decoration: none;
        border-radius: 8px;
        margin: 8px 8px 8px 0;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn:hover {
        background: var(--terre-cuite);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(160, 82, 45, 0.3);
    }

    .logout {
        color: #d9534f;
        display: inline-block;
        margin-top: 30px;
        text-decoration: none;
        font-weight: bold;
        transition: opacity 0.3s;
    }

    .logout:hover {
        opacity: 0.7;
    }
</style>
</head>
<body>

    <header>
        <img src="zoo_de_milo.png" alt="Logo Zoo de Milo" class="logo-header">
        <h1 class="header-title">Parc Zoologique de Milo</h1>
    </header>
    
    <div class="container">
        <h1>Tableau de Bord - Directeur</h1>
        
<div class="menu-section">
            <h3>Gestion des Animaux</h3>
            <a href="liste_animaux.php" class="btn">Liste des Animaux</a>
            <a href="rechercher_animal.php" class="btn">Rechercher un Animal</a>
            <a href="ajout_animal.php" class="btn">Ajouter un Animal</a>
            <a href="ajout_espece.php" class="btn">Ajouter une espèce</a>

        </div>

        <div class="menu-section">
            <h3>Gestion des Employés</h3>
            <a href="liste_employes.php" class="btn">Liste des employés</a>
            <a href="ajout_employe.php" class="btn">Ajouter un employé</a>
            <a href="rechercher_employe.php" class="btn">Rechercher un employé</a>
        </div>

        <a href="index.php" class="logout">Se déconnecter</a>
    </div>

</body>
</html>


