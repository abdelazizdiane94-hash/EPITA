<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Ticket</title>
    <link rel="stylesheet" href="../public/css/app.css">
</head>
<body>
    <div class="container">
        <h1>Détails du Ticket</h1>
        <div id="ticket-details">
            <!-- Les détails du ticket seront chargés ici via JavaScript -->
        </div>
        <button id="back-button">Retour à la liste des tickets</button>
    </div>

    <script src="../public/js/async_chat.js"></script>
    <script>
        // Fonction pour charger les détails du ticket
        function loadTicketDetails(ticketId) {
            fetch(`../api/tickets/${ticketId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const ticket = data.ticket;
                        document.getElementById('ticket-details').innerHTML = `
                            <h2>${ticket.title}</h2>
                            <p><strong>Description:</strong> ${ticket.description}</p>
                            <p><strong>Statut:</strong> ${ticket.status}</p>
                            <p><strong>Créé par:</strong> ${ticket.created_by}</p>
                            <p><strong>Date de création:</strong> ${new Date(ticket.created_at).toLocaleString()}</p>
                        `;
                    } else {
                        document.getElementById('ticket-details').innerHTML = '<p>Erreur lors du chargement des détails du ticket.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('ticket-details').innerHTML = '<p>Erreur lors du chargement des détails du ticket.</p>';
                });
        }

        // Événement pour le bouton de retour
        document.getElementById('back-button').addEventListener('click', () => {
            window.location.href = '../public/index.php'; // Redirige vers la liste des tickets
        });

        // Charger les détails du ticket avec un ID fictif pour la démonstration
        loadTicketDetails(1);
    </script>
</body>
</html>