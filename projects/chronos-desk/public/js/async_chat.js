// async_chat.js
// Ce fichier gère la fonctionnalité de chat asynchrone pour la messagerie en temps réel dans l'application ChronosDesk.

let chatMessages = document.getElementById('chat-messages');
let chatInput = document.getElementById('chat-input');
let sendButton = document.getElementById('send-button');

// Fonction pour envoyer un message
function sendMessage() {
    let message = chatInput.value.trim();
    if (message) {
        // Envoi du message via AJAX
        fetch('send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                chatInput.value = ''; // Réinitialiser le champ de saisie
                loadMessages(); // Recharger les messages
            } else {
                console.error('Erreur lors de l\'envoi du message:', data.error);
            }
        })
        .catch(error => console.error('Erreur réseau:', error));
    }
}

// Fonction pour charger les messages
function loadMessages() {
    fetch('get_messages.php')
    .then(response => response.json())
    .then(data => {
        chatMessages.innerHTML = ''; // Réinitialiser l'affichage des messages
        data.messages.forEach(msg => {
            let messageElement = document.createElement('div');
            messageElement.classList.add('chat-message');
            messageElement.textContent = msg; // Afficher le message
            chatMessages.appendChild(messageElement);
        });
    })
    .catch(error => console.error('Erreur lors du chargement des messages:', error));
}

// Événement pour le bouton d'envoi
sendButton.addEventListener('click', sendMessage);

// Charger les messages au démarrage
loadMessages();

// Recharger les messages toutes les 5 secondes
setInterval(loadMessages, 5000);