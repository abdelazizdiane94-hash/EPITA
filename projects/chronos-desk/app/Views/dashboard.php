<?php
$escape = static function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
$statusLabels = ['open' => 'Ouvert', 'in_progress' => 'En cours', 'closed' => 'Fermé'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ChronosDesk</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <header class="page-header">
        <div class="container page-heading">
            <div><p class="eyebrow">Espace assistance</p><h1>ChronosDesk</h1></div>
            <div class="account-panel">
                <p>Connecté : <strong><?= $escape($user['username']) ?></strong> · <?= $escape($user['role']) ?></p>
                <form method="post" action="?route=logout"><input type="hidden" name="csrf_token" value="<?= $escape($_SESSION['csrf_token']) ?>"><button class="button button-secondary" type="submit">Déconnexion</button></form>
            </div>
        </div>
    </header>
    <main class="container content-grid">
        <section class="panel stats-panel" id="ticket-stats" aria-labelledby="ticket-stats-title">
            <p class="eyebrow">Activité</p>
            <h2 id="ticket-stats-title">Tickets</h2>
            <p class="stat-value"><?= count($tickets) ?></p>
            <p class="muted">demande(s) enregistrée(s)</p>
        </section>
        <section class="panel form-panel" aria-labelledby="create-ticket-title">
            <p class="eyebrow">Nouvelle demande</p>
            <h2 id="create-ticket-title">Créer un ticket</h2>
            <form method="post" action="?route=create-ticket">
                <input type="hidden" name="csrf_token" value="<?= $escape($_SESSION['csrf_token']) ?>">
                <label for="ticket-title">Sujet</label>
                <input id="ticket-title" name="title" maxlength="255" required>
                <label for="ticket-description">Description</label>
                <textarea id="ticket-description" name="description" maxlength="10000" rows="4" required></textarea>
                <button class="button button-primary" type="submit">Créer le ticket</button>
            </form>
        </section>
        <section class="panel ticket-panel" id="recent-tickets" aria-labelledby="recent-tickets-title">
            <div class="section-title"><div><p class="eyebrow">Suivi</p><h2 id="recent-tickets-title">Tickets récents</h2></div></div>
            <div class="table-scroll">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sujet</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                    </tr>
                    </thead>
                    <tbody id="ticket-list">
                    <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><a href="?route=ticket/<?= (int) $ticket['id'] ?>">#<?= (int) $ticket['id'] ?></a></td>
                        <td><?= $escape($ticket['title']) ?></td>
                        <td><span class="status-badge"><?= $escape($statusLabels[$ticket['status']] ?? $ticket['status']) ?></span></td>
                        <td><?= $escape($ticket['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$tickets): ?>
                    <tr><td class="empty-state" colspan="4">Aucun ticket pour le moment. Créez votre première demande ci-dessus.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>