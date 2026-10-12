<?php /** Page d'accueil et formulaire de connexion du Zoo de Milo. */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    
    <style>
        :root{
            --vert-milo:#2d5a27;
            --vert-milo-clair:#3f7d38;
            --ocre-sable:#e0b06b;
            --ocre-sable-clair:#f2c98a;
            --blanc-casse:#fdfaf6;
        }

        body{
            font-family:'Segoe UI',system-ui,sans-serif;
            background-image:url('image_zoo_de_milo.png');
            background-size:cover;
            background-position:center;
            background-attachment:fixed;
            margin:0;
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }

        body::before{
            content:"";
            position:fixed;
            inset:0;
            background:rgba(0,0,0,0.35);
            backdrop-filter:blur(3px);
            z-index:-1;
        }

        header{
            background:linear-gradient(135deg,var(--vert-milo),#1e3d1a);
            padding:14px 28px;
            display:flex;
            align-items:center;
            gap:15px;
            border-bottom:3px solid var(--ocre-sable);
            box-shadow:0 6px 18px rgba(0,0,0,0.25);
        }

        .logo-header{
            width:60px;
            height:60px;
            border-radius:14px;
            background:white;
            padding:6px;
            box-shadow:0 6px 12px rgba(0,0,0,0.25);
        }

        .container{
            max-width:520px;
            margin:60px auto;
            padding:40px;

            background:rgba(255,255,255,0.9);
            backdrop-filter:blur(10px);

            border-radius:18px;

            box-shadow:
                0 20px 50px rgba(0,0,0,0.35),
                0 5px 10px rgba(0,0,0,0.1);

            border:1px solid rgba(255,255,255,0.3);

            animation:fadeIn .6s ease;
        }

        @keyframes fadeIn{
            from{
                opacity:0;
                transform:translateY(20px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        input,select{
            width:100%;
            padding:12px 14px;
            margin:10px 0 20px 0; /* J'ai rajouté une marge en bas pour aérer les champs */

            border-radius:8px;
            border:1px solid #dcdcdc;

            background:#fafafa;

            font-size:14px;

            transition:
                border .25s,
                box-shadow .25s,
                background .25s;

            box-sizing:border-box;
        }

        input:focus,
        select:focus{
            outline:none;
            border-color:var(--vert-milo);
            background:white;

            box-shadow:
                0 0 0 3px rgba(45,90,39,0.15);
        }

        .btn{
            background:linear-gradient(135deg,var(--vert-milo),var(--vert-milo-clair));
            color:white;

            padding:12px;
            border:none;
            border-radius:10px;

            cursor:pointer;
            width:48%;

            font-weight:600;
            letter-spacing:.3px;

            transition:
                transform .15s,
                box-shadow .15s,
                background .3s;
        }

        .btn:hover{
            background:linear-gradient(135deg,var(--vert-milo-clair),var(--vert-milo));
            transform:translateY(-2px);

            box-shadow:0 8px 20px rgba(0,0,0,0.25);
        }

        .btn:active{
            transform:translateY(1px);
            box-shadow:0 3px 10px rgba(0,0,0,0.2);
        }

        h1,h2{
            margin-top:0;
            color:var(--vert-milo);
            text-align: center; /* Ajouté pour centrer le titre proprement */
        }

        @media (max-width:600px){
            .container{
                margin:30px 20px;
                padding:30px;
            }

            .btn{
                width:100%;
                margin-top:8px;
            }
        }
        
        /* Petits styles ajoutés pour structurer le formulaire sans modifier ton CSS de base */
        .form-group {
            margin-bottom: 15px;
            font-weight: bold;
            color: #333;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .link-container {
            text-align: center;
            margin-top: 25px;
        }
        .link-container a {
            color: var(--vert-milo);
            text-decoration: none;
            font-weight: bold;
        }
        .link-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    
    <header>
        <div class="logo-header"></div>
        <h2 style="color: white; margin: 0;">Zoo de Milo</h2>
    </header>

    <div class="container">
        <form action="login.php" method="post">
            <h1>Se connecter</h1>
            
            <div class="form-group">
                <label for="login">Nom de Login :</label>
                <input type="text" id="login" name="login">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password">
            </div>
        
            <div class="button-group">
                <input type="submit" class="btn" value="Identifiez-vous">
                <input type="reset" class="btn" value="Annuler" style="background: linear-gradient(135deg, #7f8c8d, #95a5a6);">
            </div>
            
            <div class="link-container">
                <a href="enregistrer.html">S'enregistrer</a>
            </div>
            
        </form>
    </div>
    
</body>
</html>