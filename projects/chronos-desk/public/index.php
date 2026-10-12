<?php
session_set_cookie_params(['httponly' => true, 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'samesite' => 'Lax']);
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../app/Models/Database.php';
require_once __DIR__ . '/../app/Models/Ticket.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/TicketController.php';

$route = (string) ($_GET['route'] ?? 'dashboard');
try {
    $database = (new Database())->getConnection();
} catch (Throwable $exception) {
    error_log('ChronosDesk database connection failed: ' . $exception->getMessage());
    http_response_code(503);
    exit('ChronosDesk est indisponible pour le moment. Vérifiez la configuration de la base de données.');
}
$authController = new AuthController($database);
$ticketController = new TicketController($database);

if ($route === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validToken = hash_equals($_SESSION['csrf_token'], (string) ($_POST['csrf_token'] ?? ''));
        $user = $validToken ? $authController->login($_POST['username'] ?? '', $_POST['password'] ?? '') : false;
        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            header('Location: ?route=dashboard');
            exit;
        }
        http_response_code(401);
        $loginError = 'Identifiants invalides ou formulaire expiré.';
    }
    $csrfToken = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $errorMessage = htmlspecialchars($loginError ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    echo '<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - ChronosDesk</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <main class="login-shell">
        <section class="panel login-panel">
            <p class="eyebrow">Espace assistance</p>
            <h1>Connexion ChronosDesk</h1>
            <p class="error-message" role="alert">' . $errorMessage . '</p>
            <form method="post">
                <input type="hidden" name="csrf_token" value="' . $csrfToken . '">
                <label for="username">Identifiant</label>
                <input id="username" name="username" required maxlength="50" autocomplete="username">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                <button class="button button-primary" type="submit">Se connecter</button>
            </form>
        </section>
    </main>
</body>
</html>';
    exit;
}

if ($route === 'logout') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        http_response_code(405);
        exit('Utilisez le formulaire de déconnexion.');
    }
    if (!hash_equals($_SESSION['csrf_token'], (string) ($_POST['csrf_token'] ?? ''))) {
        http_response_code(403);
        exit('Le formulaire de déconnexion a expiré. Actualisez la page puis réessayez.');
    }
    $authController->logout();
    header('Location: ?route=login');
    exit;
}

if ($route === 'create-ticket') {
    $ticketController->create();
} elseif (preg_match('/^ticket\/(\d+)\/status$/', $route, $matches)) {
    $ticketController->update($matches[1]);
} elseif (preg_match('/^ticket\/(\d+)$/', $route, $matches)) {
    $ticketController->show($matches[1]);
} else {
    $ticketController->index();
}