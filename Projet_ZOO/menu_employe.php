<?php /** Menu Employé : accès à la consultation du cheptel. */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Employé - Parc Zoologique de Milo</title>
<style>
    :root {
        --vert-milo: #2d5a27;
        --ocre-sable: #e0b06b;
        --terre-cuite: #a0522d;
        --blanc-casse: #fdfaf6;
        --shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    body {
        font-family: 'Segoe UI', system-ui, sans-serif;
        background: url('image_zoo_de_milo.png') center/cover fixed;
        margin: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    header {
        background: var(--vert-milo);
        padding: 20px 30px;
        display: flex;
        align-items: center;
        border-bottom: 4px solid var(--ocre-sable);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .logo-header {
        width: 50px;
        height: 50px;
        margin-right: 20px;
        border-radius: 10px;
        background: rgba(255,255,255,0.1);
    }

    .header-title {
        color: white;
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .container {
        max-width: 600px;
        margin: 60px auto;
        background: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: var(--shadow);
        text-align: center;
    }

    .btn {
        display: inline-block;
        background: var(--vert-milo);
        color: white;
        padding: 12px 24px;
        text-decoration: none;
        border-radius: 8px;
        margin: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn:hover {
        background: var(--terre-cuite);
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(160, 82, 45, 0.3);
    }

    .logout {
        color: #d9534f;
        display: block;
        margin-top: 40px;
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
        <h2>Espace Employé</h2>
        <p style="color: #666;">Gestion du parc</p>
        
        <div style="margin-top: 20px;">
            <a href="liste_animaux.php" class="btn">Liste des Animaux</a>
            <a href="rechercher_animal.php" class="btn">Rechercher un animal</a>
        </div>

        <a href="index.php" class="logout">Se déconnecter</a>
    </div>

</body>
</html>