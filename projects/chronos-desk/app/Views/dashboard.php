<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ChronosDesk</title>
    <link rel="stylesheet" href="../public/css/app.css">
</head>
<body>
    <header>
        <h1>Tableau de Bord - ChronosDesk</h1>
    </header>
    <main>
        <section id="user-stats">
            <h2>Statistiques Utilisateur</h2>
            <div id="user-info">
                <!-- Informations sur l'utilisateur connecté -->
                <p>Nom: <span id="user-name"></span></p>
                <p>Rôle: <span id="user-role"></span></p>
            </div>
        </section>
        <section id="ticket-stats">
            <h2>Statistiques des Tickets</h2>
            <div id="ticket-summary">
                <p>Tickets Ouverts: <span id="open-tickets"></span></p>
                <p>Tickets Fermés: <span id="closed-tickets"></span></p>
                <p>Tickets en Attente: <span id="pending-tickets"></span></p>
            </div>
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
                    <!-- Liste des tickets récents -->
                </tbody>
            </table>
        </section>
    </main>
    <script src="../public/js/async_chat.js"></script>
    <script src="../public/js/sla_timer.js"></script>
    <script>
        // Logique pour récupérer et afficher les statistiques des utilisateurs et des tickets
        document.addEventListener('DOMContentLoaded', function() {
            // Simulation de données pour le tableau de bord
            document.getElementById('user-name').textContent = 'Abdel-Aziz DIANÉ';
            document.getElementById('user-role').textContent = 'Technicien';
            document.getElementById('open-tickets').textContent = '5';
            document.getElementById('closed-tickets').textContent = '10';
            document.getElementById('pending-tickets').textContent = '2';

            // Ajout de tickets récents
            const tickets = [
                { id: 1, subject: 'Problème de connexion', status: 'Ouvert', created_at: '2023-10-01' },
                { id: 2, subject: 'Erreur de serveur', status: 'Fermé', created_at: '2023-09-30' },
                { id: 3, subject: 'Demande de fonctionnalité', status: 'En Attente', created_at: '2023-10-02' }
            ];

            const ticketList = document.getElementById('ticket-list');
            tickets.forEach(ticket => {
                const row = document.createElement('tr');
                row.innerHTML = `<td>${ticket.id}</td><td>${ticket.subject}</td><td>${ticket.status}</td><td>${ticket.created_at}</td>`;
                ticketList.appendChild(row);
            });
        });
    </script>
</body>
</html>