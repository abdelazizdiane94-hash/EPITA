document.addEventListener('DOMContentLoaded', () => {
    const storageKey = 'collab-board-state-v1';
    const validTypes = ['start', 'stop', 'continue'];
    const containers = [...document.querySelectorAll('.card-container')];
    const voteStatus = document.getElementById('vote-status');
    const boardStatus = document.getElementById('board-status');
    const sampleCards = [
        { title: 'Documenter les décisions', description: 'Garder une trace des choix techniques.', type: 'start', votes: 0 },
        { title: 'Réduire les réunions', description: 'Partager un ordre du jour en amont.', type: 'stop', votes: 0 },
        { title: 'Revue de code utile', description: 'Les retours sont rapides et concrets.', type: 'continue', votes: 0 }
    ];
    let cards = [];
    let nextCardId = 1;
    let storageAvailable = true;

    function isValidBoard(value) {
        return Array.isArray(value)
            && value.length <= 100
            && value.every(card =>
                card
                && Number.isSafeInteger(card.id)
                && card.id > 0
                && typeof card.title === 'string'
                && card.title.trim() !== ''
                && card.title.length <= 100
                && typeof card.description === 'string'
                && card.description.length <= 500
                && validTypes.includes(card.type)
                && Number.isInteger(card.votes)
                && card.votes >= 0
                && card.votes <= 5
            )
            && new Set(value.map(card => card.id)).size === value.length
            && value.reduce((total, card) => total + card.votes, 0) <= 5;
    }

    function saveBoard() {
        if (!storageAvailable) return false;
        try {
            localStorage.setItem(storageKey, JSON.stringify(cards));
            return true;
        } catch (error) {
            storageAvailable = false;
            boardStatus.textContent = 'Impossible de sauvegarder le tableau dans ce navigateur. Les changements resteront temporaires.';
            return false;
        }
    }

    function updateVotes() {
        const votesUsed = cards.reduce((total, card) => total + card.votes, 0);
        voteStatus.textContent = `Votes utilisés : ${votesUsed}/5`;
        document.querySelectorAll('.vote-button').forEach(button => {
            button.disabled = votesUsed >= 5;
        });
    }

    function renderBoard(focusCardId = null) {
        containers.forEach(container => container.replaceChildren());

        cards.forEach(cardData => {
            const card = document.createElement('article');
            card.className = 'board-card';
            card.id = `retro-card-${cardData.id}`;
            card.draggable = true;

            const heading = document.createElement('h4');
            heading.textContent = cardData.title;
            const detail = document.createElement('p');
            detail.textContent = cardData.description;
            if (!cardData.description) detail.hidden = true;

            const controls = document.createElement('div');
            controls.className = 'card-controls';
            const voteCount = document.createElement('span');
            voteCount.textContent = `${cardData.votes} vote${cardData.votes === 1 ? '' : 's'}`;
            const voteButton = document.createElement('button');
            voteButton.type = 'button';
            voteButton.className = 'vote-button';
            voteButton.textContent = 'Voter';
            voteButton.setAttribute('aria-label', `Voter pour ${cardData.title}`);
            voteButton.disabled = cards.reduce((total, item) => total + item.votes, 0) >= 5;
            voteButton.addEventListener('click', () => {
                const currentCard = cards.find(item => item.id === cardData.id);
                if (!currentCard || cards.reduce((total, item) => total + item.votes, 0) >= 5) return;
                currentCard.votes += 1;
                saveBoard();
                voteCount.textContent = `${currentCard.votes} vote${currentCard.votes === 1 ? '' : 's'}`;
                updateVotes();
            });

            const moveLabel = document.createElement('label');
            moveLabel.className = 'visually-hidden';
            moveLabel.htmlFor = `move-card-${cardData.id}`;
            moveLabel.textContent = `Déplacer ${cardData.title}`;
            const moveSelect = document.createElement('select');
            moveSelect.id = `move-card-${cardData.id}`;
            moveSelect.className = 'move-card';
            moveSelect.setAttribute('aria-label', `Déplacer ${cardData.title}`);
            validTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type;
                option.textContent = type[0].toUpperCase() + type.slice(1);
                option.selected = cardData.type === type;
                moveSelect.appendChild(option);
            });
            moveSelect.addEventListener('change', event => {
                cardData.type = event.target.value;
                saveBoard();
                renderBoard(cardData.id);
                boardStatus.textContent = storageAvailable
                    ? `Carte déplacée vers ${cardData.type}.`
                    : `Carte déplacée vers ${cardData.type} en mémoire. La sauvegarde locale est indisponible.`;
            });

            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.className = 'delete-card';
            deleteButton.textContent = 'Supprimer';
            deleteButton.setAttribute('aria-label', `Supprimer ${cardData.title}`);
            deleteButton.addEventListener('click', () => {
                cards = cards.filter(item => item.id !== cardData.id);
                saveBoard();
                renderBoard();
                boardStatus.textContent = storageAvailable
                    ? 'Carte supprimée.'
                    : 'Carte supprimée en mémoire. La sauvegarde locale est indisponible.';
            });

            controls.append(voteCount, voteButton, moveLabel, moveSelect, deleteButton);
            card.append(heading, detail, controls);
            card.addEventListener('dragstart', event => {
                event.dataTransfer.setData('text/plain', String(cardData.id));
                event.dataTransfer.effectAllowed = 'move';
            });
            containers.find(container => container.dataset.type === cardData.type)?.appendChild(card);
        });

        updateVotes();
        if (focusCardId !== null) {
            document.getElementById(`move-card-${focusCardId}`)?.focus();
        }
    }

    function addCard(title, description, type) {
        const cleanTitle = title.trim();
        if (!cleanTitle || !validTypes.includes(type) || cards.length >= 100) return false;
        cards.push({ id: nextCardId++, title: cleanTitle, description: description.trim(), type, votes: 0 });
        saveBoard();
        renderBoard();
        return true;
    }

    containers.forEach(container => {
        container.addEventListener('dragover', event => {
            event.preventDefault();
            container.classList.add('drag-over');
        });
        container.addEventListener('dragleave', event => {
            if (!container.contains(event.relatedTarget)) container.classList.remove('drag-over');
        });
        container.addEventListener('drop', event => {
            event.preventDefault();
            container.classList.remove('drag-over');
            const cardId = Number(event.dataTransfer.getData('text/plain'));
            const card = cards.find(item => item.id === cardId);
            if (!card || !validTypes.includes(container.dataset.type)) return;
            card.type = container.dataset.type;
            saveBoard();
            renderBoard();
            boardStatus.textContent = storageAvailable
                ? `Carte déplacée vers ${card.type}.`
                : `Carte déplacée vers ${card.type} en mémoire. La sauvegarde locale est indisponible.`;
        });
    });

    document.querySelectorAll('[data-add-card]').forEach(button => {
        button.addEventListener('click', () => {
            const form = document.getElementById(button.getAttribute('aria-controls'));
            const opening = form.hidden;
            form.hidden = !opening;
            button.setAttribute('aria-expanded', String(opening));
            if (opening) form.querySelector('[name="title"]').focus();
        });
    });

    document.querySelectorAll('[data-card-form]').forEach(form => {
        form.addEventListener('submit', event => {
            event.preventDefault();
            const title = form.elements.title.value;
            const description = form.elements.description.value;
            if (!addCard(title, description, form.dataset.cardForm)) {
                boardStatus.textContent = cards.length >= 100 ? 'Le tableau est limité à 100 cartes.' : 'Saisissez un titre valide.';
                return;
            }
            form.reset();
            form.hidden = true;
            document.querySelector(`[aria-controls="${form.id}"]`).setAttribute('aria-expanded', 'false');
            boardStatus.textContent = storageAvailable
                ? 'Carte ajoutée et enregistrée dans ce navigateur.'
                : 'Carte ajoutée, mais la sauvegarde locale est indisponible.';
        });
    });

    document.getElementById('reset-board').addEventListener('click', () => {
        if (!window.confirm('Réinitialiser le tableau et supprimer vos modifications enregistrées ?')) return;
        cards = sampleCards.map(card => ({ id: nextCardId++, ...card }));
        saveBoard();
        renderBoard();
        boardStatus.textContent = storageAvailable
            ? 'Tableau réinitialisé avec les cartes d’exemple.'
            : 'Tableau réinitialisé en mémoire. La sauvegarde locale est indisponible.';
    });

    document.getElementById('export-button').addEventListener('click', () => {
        const sections = validTypes.map(type => {
            const name = type[0].toUpperCase() + type.slice(1);
            const cardsInColumn = cards.filter(card => card.type === type).map(card =>
                `- **${card.title}** (${card.votes} vote${card.votes === 1 ? '' : 's'})${card.description ? `\n  ${card.description}` : ''}`
            );
            return `## ${name}\n\n${cardsInColumn.length ? cardsInColumn.join('\n') : '- Aucune carte'}`;
        });
        const reportDate = new Date().toLocaleDateString('fr-FR');
        const blob = new Blob([`# Rapport de rétrospective — ${reportDate}\n\n${sections.join('\n\n')}\n`], { type: 'text/markdown;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'rapport-retrospective.md';
        link.click();
        window.setTimeout(() => URL.revokeObjectURL(url), 1000);
    });

    document.getElementById('current-year').textContent = String(new Date().getFullYear());
    let shouldSaveInitialBoard = false;
    let savedBoard;
    try {
        savedBoard = localStorage.getItem(storageKey);
    } catch (error) {
        storageAvailable = false;
        cards = sampleCards.map(card => ({ id: nextCardId++, ...card }));
        boardStatus.textContent = 'La sauvegarde locale est indisponible. Les changements resteront temporaires.';
    }
    if (storageAvailable && savedBoard === null) {
        cards = sampleCards.map(card => ({ id: nextCardId++, ...card }));
        boardStatus.textContent = 'Cartes d’exemple chargées. Les changements sont enregistrés dans ce navigateur.';
        shouldSaveInitialBoard = true;
    } else if (storageAvailable) {
        try {
            const parsedBoard = JSON.parse(savedBoard);
            if (isValidBoard(parsedBoard)) {
                cards = parsedBoard;
                nextCardId = cards.reduce((maximum, card) => Math.max(maximum, card.id), 0) + 1;
                boardStatus.textContent = 'Tableau restauré depuis ce navigateur.';
            } else {
                cards = sampleCards.map(card => ({ id: nextCardId++, ...card }));
                boardStatus.textContent = 'Les données enregistrées sont invalides. Les cartes d’exemple sont chargées.';
            }
        } catch (error) {
            cards = sampleCards.map(card => ({ id: nextCardId++, ...card }));
            boardStatus.textContent = 'La sauvegarde est illisible. Les cartes d’exemple sont chargées.';
        }
    }
    renderBoard();
    if (shouldSaveInitialBoard) saveBoard();
});
