<?php
$escape = static function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
$statusLabels = ['open' => 'Ouvert', 'in_progress' => 'En cours', 'closed' => 'Fermé'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #<?= (int) $ticket['id'] ?> - ChronosDesk</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <main class="container panel ticket-detail">
        <p class="eyebrow">Assistance · Ticket #<?= (int) $ticket['id'] ?></p>
        <h1>Détails du ticket</h1>
        <h2><?= $escape($ticket['title']) ?></h2>
        <p class="ticket-description"><?= $escape($ticket['description']) ?></p>
        <p>Statut : <span class="status-badge"><?= $escape($statusLabels[$ticket['status']] ?? $ticket['status']) ?></span></p>
        <p>Créé par <?= $escape($ticket['username']) ?> le <?= $escape($ticket['created_at']) ?></p>
        <?php if (in_array($_SESSION['user']['role'], ['Technicien', 'Admin'], true)): ?>
        <form class="status-form" method="post" action="?route=ticket/<?= (int) $ticket['id'] ?>/status">
            <input type="hidden" name="csrf_token" value="<?= $escape($_SESSION['csrf_token']) ?>">
            <label for="ticket-status">Statut
                <select id="ticket-status" name="status">
                    <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Ouvert</option>
                    <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>En cours</option>
                    <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Fermé</option>
                </select>
            </label>
            <button class="button button-primary" type="submit">Mettre à jour</button>
        </form>
        <?php endif; ?>
        <a href="?route=dashboard">← Retour à la liste des tickets</a>
    </main>
</body>
</html>