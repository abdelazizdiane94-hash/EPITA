<?php $escape = static function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ChronosDesk</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <header>
        <h1>Tableau de Bord - ChronosDesk</h1>
        <p>Connecté : <?= $escape($user['username']) ?> (<?= $escape($user['role']) ?>)</p>
        <form method="post" action="?route=logout"><input type="hidden" name="csrf_token" value="<?= $escape($_SESSION['csrf_token']) ?>"><button type="submit">Déconnexion</button></form>
    </header>
    <main>
        <section id="user-stats">
            <h2>Statistiques Utilisateur</h2>
            <div id="user-info">
                <p>Nom: <span id="user-name"><?= $escape($user['username']) ?></span></p>
                <p>Rôle: <span id="user-role"><?= $escape($user['role']) ?></span></p>
            </div>
        </section>
        <section id="ticket-stats">
            <h2>Statistiques des Tickets</h2>
            <div id="ticket-summary"><p>Tickets affichés: <?= count($tickets) ?></p></div>
        </section>
        <section>
            <h2>Créer un ticket</h2>
            <form method="post" action="?route=create-ticket">
                <input type="hidden" name="csrf_token" value="<?= $escape($_SESSION['csrf_token']) ?>">
                <label>Sujet <input name="title" maxlength="255" required></label>
                <label>Description <textarea name="description" maxlength="10000" required></textarea></label>
                <button type="submit">Créer</button>
            </form>
        </section>
        <section id="recent-tickets">
            <h2>Tickets Récents</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sujet</th>
                        <th>Statut</th>
                            <th>Date de Création</th>
                    </tr>
                </thead>
                <tbody id="ticket-list">
                    <?php foreach ($tickets as $ticket): ?>
                    <tr><td><a href="?route=ticket/<?= (int) $ticket['id'] ?>"><?= (int) $ticket['id'] ?></a></td><td><?= $escape($ticket['title']) ?></td><td><?= $escape($ticket['status']) ?></td><td><?= $escape($ticket['created_at']) ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>