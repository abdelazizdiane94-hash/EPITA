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

document.addEventListener('DOMContentLoaded', () => {
    const containers = [...document.querySelectorAll('.card-container')];
    const sampleCards = [
        { title: 'Documenter les décisions', description: 'Garder une trace des choix techniques.', type: 'start' },
        { title: 'Réduire les réunions', description: 'Partager un ordre du jour en amont.', type: 'stop' },
        { title: 'Revue de code utile', description: 'Les retours sont rapides et concrets.', type: 'continue' }
    ];
    let votesUsed = 0;
    let nextCardId = 1;
    const voteStatus = document.getElementById('vote-status');

    function updateVotes() {
        voteStatus.textContent = `Votes utilisés : ${votesUsed}/5`;
        document.querySelectorAll('.vote-button').forEach(button => { button.disabled = votesUsed >= 5; });
    }

    function createCard(title, description, type) {
        const card = document.createElement('article');
        card.className = 'board-card';
        card.id = `retro-card-${nextCardId++}`;
        card.draggable = true;
        card.dataset.votes = '0';
        const heading = document.createElement('h4');
        heading.textContent = title;
        const detail = document.createElement('p');
        detail.textContent = description;
        const voteArea = document.createElement('div');
        voteArea.className = 'vote-section';
        const count = document.createElement('span');
        count.textContent = '0 vote';
        const voteButton = document.createElement('button');
        voteButton.type = 'button';
        voteButton.className = 'vote-button';
        voteButton.textContent = 'Voter';
        voteButton.addEventListener('click', () => {
            if (votesUsed >= 5) return;
            votesUsed += 1;
            const votes = Number(card.dataset.votes) + 1;
            card.dataset.votes = String(votes);
            count.textContent = `${votes} vote${votes > 1 ? 's' : ''}`;
            updateVotes();
        });
        voteArea.append(count, voteButton);
        card.append(heading, detail, voteArea);
        card.addEventListener('dragstart', event => {
            event.dataTransfer.setData('text/plain', card.id);
            event.dataTransfer.effectAllowed = 'move';
        });
        containers.find(container => container.dataset.type === type).appendChild(card);
    }

    sampleCards.forEach(card => createCard(card.title, card.description, card.type));
    containers.forEach(container => {
        container.addEventListener('dragover', event => event.preventDefault());
        container.addEventListener('drop', event => {
            event.preventDefault();
            const card = document.getElementById(event.dataTransfer.getData('text/plain'));
            if (card) container.appendChild(card);
        });
    });

    window.addCard = type => {
        const title = window.prompt('Titre de la carte :');
        if (!title || !title.trim()) return;
        const description = window.prompt('Description :') || '';
        createCard(title.trim(), description.trim(), type);
    };

    document.getElementById('export-button').addEventListener('click', () => {
        const sections = containers.map(container => {
            const name = container.dataset.type[0].toUpperCase() + container.dataset.type.slice(1);
            const cardsInColumn = [...container.querySelectorAll('.board-card')].map(card => {
                const title = card.querySelector('h4').textContent;
                const description = card.querySelector('p').textContent;
                return `- **${title}** (${card.dataset.votes} vote(s))${description ? `\n  ${description}` : ''}`;
            });
            return `## ${name}\n\n${cardsInColumn.length ? cardsInColumn.join('\n') : '- Aucune carte'}`;
        });
        const blob = new Blob([`# Rapport de rétrospective\n\n${sections.join('\n\n')}\n`], { type: 'text/markdown;charset=utf-8' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'rapport-retrospective.md';
        link.click();
        window.setTimeout(() => URL.revokeObjectURL(link.href), 1000);
    });
    updateVotes();
});