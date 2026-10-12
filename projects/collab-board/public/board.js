// Fichier JavaScript pour gérer la fonctionnalité de Drag & Drop et le système de vote dans l'application CollabBoard

// Initialisation des variables
const board = document.getElementById('board');
const cards = document.querySelectorAll('.card');
const voteButtons = document.querySelectorAll('.vote-button');

// Fonction pour gérer le Drag & Drop
function handleDragStart(event) {
    event.dataTransfer.setData('text/plain', event.target.id);
}

function handleDragOver(event) {
    event.preventDefault();
}

function handleDrop(event) {
    event.preventDefault();
    const cardId = event.dataTransfer.getData('text/plain');
    const card = document.getElementById(cardId);
    board.appendChild(card);
}

// Ajout des écouteurs d'événements pour le Drag & Drop
cards.forEach(card => {
    card.addEventListener('dragstart', handleDragStart);
    card.addEventListener('dragover', handleDragOver);
    card.addEventListener('drop', handleDrop);
});

// Fonction pour gérer le système de vote
function handleVote(event) {
    const cardId = event.target.dataset.cardId;
    const currentVotes = parseInt(event.target.dataset.votes) || 0;
    const newVotes = currentVotes + 1;

    // Mettre à jour l'affichage des votes
    event.target.dataset.votes = newVotes;
    event.target.textContent = `Votes: ${newVotes}`;

    // Appel à l'API pour sauvegarder le vote
    fetch('api/sync.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cardId: cardId, votes: newVotes })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Vote sauvegardé:', data);
    })
    .catch(error => {
        console.error('Erreur lors de la sauvegarde du vote:', error);
    });
}

// Ajout des écouteurs d'événements pour les boutons de vote
voteButtons.forEach(button => {
    button.addEventListener('click', handleVote);
});