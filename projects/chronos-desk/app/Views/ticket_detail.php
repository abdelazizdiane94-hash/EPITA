<?php $escape = static function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Ticket</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <div class="container">
        <h1>Détails du Ticket #<?= (int) $ticket['id'] ?></h1>
        <h2><?= $escape($ticket['title']) ?></h2>
        <p><?= nl2br($escape($ticket['description'])) ?></p>
        <p>Statut : <?= $escape($ticket['status']) ?></p>
        <p>Créé par <?= $escape($ticket['username']) ?> le <?= $escape($ticket['created_at']) ?></p>
        <?php if (in_array($_SESSION['user']['role'], ['Technicien', 'Admin'], true)): ?>
        <form method="post" action="?route=ticket/<?= (int) $ticket['id'] ?>/status">
            <input type="hidden" name="csrf_token" value="<?= $escape($_SESSION['csrf_token']) ?>">
            <label>Statut <select name="status"><option value="open">Ouvert</option><option value="in_progress">En cours</option><option value="closed">Fermé</option></select></label>
            <button type="submit">Mettre à jour</button>
        </form>
        <?php endif; ?>
        <a href="?route=dashboard">Retour à la liste des tickets</a>
    </div>
</body>
</html>