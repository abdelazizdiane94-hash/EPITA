document.addEventListener('DOMContentLoaded', () => {
    const modal = document.createElement('section');
    modal.className = 'modal';
    modal.id = 'chronos-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-labelledby', 'chronos-title');
    modal.setAttribute('aria-describedby', 'demo-description');
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML = `<div class="modal-content chronos-demo">
        <button class="modal-close" type="button" aria-label="Fermer">&times;</button>
        <h2 id="chronos-title">Démonstration interactive</h2>
        <p id="demo-description">Simulation locale, indépendante du serveur PHP.</p>
        <label for="demo-role">Rôle actif</label>
        <select id="demo-role"><option>Collaborateur</option><option>Technicien</option><option>Admin</option></select>
        <p class="role-status" aria-live="polite"></p>
        <form id="ticket-form"><label for="ticket-title">Nouveau ticket</label><input id="ticket-title" maxlength="100" required placeholder="Sujet du ticket"><button type="submit">Créer</button></form>
        <ul id="demo-tickets" aria-live="polite"></ul>
        <p>SLA restant : <strong id="sla-display">05:00</strong></p>
        <section class="demo-chat"><h3>Chat asynchrone simulé</h3><div id="chat-messages" aria-live="polite"></div><form id="chat-form"><input id="chat-input" maxlength="300" required placeholder="Votre message"><button type="submit">Envoyer</button></form></section>
    </div>`;
    document.body.appendChild(modal);

    const zooSpecies = {
        'Lion': './Projet_ZOO/lion.png',
        'Tigre': './Projet_ZOO/tiger.png',
        'Girafe': './Projet_ZOO/girafe.png',
        'Crocodile': './Projet_ZOO/crocodile.png',
        'Aigle royal': './Projet_ZOO/eagle.png',
        'Rhinocéros': './Projet_ZOO/rhinoceros.png'
    };
    const zooAnimals = [
        { id: 1, name: 'Simba', species: 'Lion', enclosure: 'Savane A1' },
        { id: 2, name: 'Shere Khan', species: 'Tigre', enclosure: 'Jungle B2' },
        { id: 3, name: 'Melman', species: 'Girafe', enclosure: 'Savane A2' },
        { id: 4, name: 'Sobek', species: 'Crocodile', enclosure: 'Marais C1' },
        { id: 5, name: 'Altair', species: 'Aigle royal', enclosure: 'Volière D1' },
        { id: 6, name: 'Rino', species: 'Rhinocéros', enclosure: 'Savane A3' }
    ];
    let zooRole = 'Directeur';
    let nextZooId = 7;

    let remainingSeconds = 300;
    let demoOpener = null;

    function formatTime(seconds) {
        return `${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;
    }

    function renderZooCards() {
        const grid = modal.querySelector('#zoo-animal-grid');
        if (!grid) return;
        const query = modal.querySelector('#zoo-search').value.trim().toLocaleLowerCase('fr');
        const visibleAnimals = zooAnimals.filter(animal => `${animal.name} ${animal.species} ${animal.enclosure}`.toLocaleLowerCase('fr').includes(query));
        grid.replaceChildren();
        modal.querySelector('#zoo-result-count').textContent = `Animaux trouvés : ${visibleAnimals.length}`;

        if (!visibleAnimals.length) {
            const empty = document.createElement('p');
            empty.className = 'zoo-empty';
            empty.textContent = 'Aucun animal ne correspond à cette recherche.';
            grid.appendChild(empty);
            return;
        }

        visibleAnimals.forEach(animal => {
            const card = document.createElement('article');
            card.className = 'zoo-card';
            const image = document.createElement('img');
            image.src = zooSpecies[animal.species];
            image.alt = animal.species;
            image.loading = 'lazy';
            const details = document.createElement('div');
            const name = document.createElement('strong');
            name.textContent = animal.name;
            const species = document.createElement('p');
            species.textContent = `Espèce : ${animal.species}`;
            const enclosure = document.createElement('p');
            enclosure.textContent = `Enclos : ${animal.enclosure}`;
            details.append(name, species, enclosure);
            card.append(image, details);

            if (zooRole === 'Directeur') {
                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.className = 'zoo-delete';
                deleteButton.textContent = 'Supprimer';
                deleteButton.setAttribute('aria-label', `Supprimer ${animal.name}`);
                deleteButton.addEventListener('click', () => {
                    const animalIndex = zooAnimals.findIndex(item => item.id === animal.id);
                    if (animalIndex !== -1) zooAnimals.splice(animalIndex, 1);
                    renderZooCards();
                });
                card.appendChild(deleteButton);
            }
            grid.appendChild(card);
        });
    }

    function renderZooDemo() {
        modal.querySelector('#demo-description').textContent = 'Simulation locale des menus Directeur et Employé; aucune connexion PHP ou base de données requise.';
        modal.querySelector('.chronos-demo').innerHTML = `
            <button class="modal-close" type="button" aria-label="Fermer">&times;</button>
            <h2 id="chronos-title">Projet_ZOO | Zoo de Milo</h2>
            <p id="demo-description">Simulation des menus PHP.</p>
            <div class="zoo-toolbar">
                <label for="zoo-role">Session simulée
                    <select id="zoo-role"><option value="Directeur">Directeur</option><option value="Employé">Employé</option></select>
                </label>
                <label for="zoo-search">Rechercher animal ou espèce
                    <input id="zoo-search" type="search" placeholder="Nom, espèce ou enclos">
                </label>
                <form id="zoo-add-form">
                    <label for="zoo-name">Nom
                        <input id="zoo-name" maxlength="50" required placeholder="Nom de l'animal">
                    </label>
                    <label for="zoo-species">Espèce
                        <select id="zoo-species">${Object.keys(zooSpecies).map(species => `<option>${species}</option>`).join('')}</select>
                    </label>
                    <label for="zoo-enclosure">Enclos
                        <input id="zoo-enclosure" maxlength="50" placeholder="Nouvel enclos">
                    </label>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
            <p id="zoo-role-status" class="role-status" aria-live="polite"></p>
            <p id="zoo-result-count" aria-live="polite"></p>
            <div id="zoo-animal-grid" class="zoo-grid"></div>
        `;
        const zooModal = modal.querySelector('.chronos-demo');
        zooModal.querySelector('.modal-close').addEventListener('click', closeDemo);
        modal.querySelector('#zoo-search').addEventListener('input', renderZooCards);
        modal.querySelector('#zoo-role').value = zooRole;
        modal.querySelector('#zoo-role').addEventListener('change', event => {
            zooRole = event.target.value;
            modal.querySelector('#zoo-add-form').hidden = zooRole !== 'Directeur';
            modal.querySelector('#zoo-role-status').textContent = zooRole === 'Directeur' ? 'Directeur : consultation, ajout et suppression.' : 'Employé : consultation et recherche des animaux.';
            renderZooCards();
        });
        modal.querySelector('#zoo-add-form').addEventListener('submit', event => {
            event.preventDefault();
            if (zooRole !== 'Directeur') return;
            const nameInput = modal.querySelector('#zoo-name');
            const enclosureInput = modal.querySelector('#zoo-enclosure');
            const species = modal.querySelector('#zoo-species').value;
            const name = nameInput.value.trim();
            if (!name) return;
            zooAnimals.unshift({ id: nextZooId++, name, species, enclosure: enclosureInput.value.trim() || 'Enclos à attribuer' });
            event.target.reset();
            renderZooCards();
            modal.querySelector('#zoo-role-status').textContent = 'Animal ajouté à la démonstration locale.';
        });
        modal.querySelector('#zoo-role').dispatchEvent(new Event('change'));
        renderZooCards();
    }

    function closeDemo() {
        if (modal.getAttribute('aria-hidden') === 'true') return;
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        if (demoOpener && demoOpener.isConnected) demoOpener.focus();
    }

    function openDemo(project) {
        demoOpener = document.activeElement;
        if (project === 'zoo') {
            modal.querySelector('#chronos-title').textContent = 'Projet_ZOO | Zoo de Milo';
            renderZooDemo();
        } else {
            modal.querySelector('.chronos-demo').innerHTML = `
                <button class="modal-close" type="button" aria-label="Fermer">&times;</button>
                <h2 id="chronos-title">ChronosDesk | Démonstration</h2>
                <p id="demo-description">Simulation locale, indépendante du serveur PHP.</p>
                <label for="demo-role">Rôle actif</label>
                <select id="demo-role"><option>Collaborateur</option><option>Technicien</option><option>Admin</option></select>
                <p class="role-status" aria-live="polite"></p>
                <form id="ticket-form"><label for="ticket-title">Nouveau ticket</label><input id="ticket-title" maxlength="100" required placeholder="Sujet du ticket"><button type="submit">Créer</button></form>
                <ul id="demo-tickets" aria-live="polite"></ul>
                <p>SLA restant : <strong id="sla-display">05:00</strong></p>
                <section class="demo-chat"><h3>Chat asynchrone simulé</h3><div id="chat-messages" aria-live="polite"></div><form id="chat-form"><input id="chat-input" maxlength="300" required placeholder="Votre message"><button type="submit">Envoyer</button></form></section>
            `;
            remainingSeconds = 300;
            attachChronosHandlers();
        }
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');
        modal.querySelector('.modal-close').focus();
    }

    function attachChronosHandlers() {
        const rolePicker = modal.querySelector('#demo-role');
        const ticketForm = modal.querySelector('#ticket-form');
        const ticketTitle = modal.querySelector('#ticket-title');
        const ticketList = modal.querySelector('#demo-tickets');
        const roleStatus = modal.querySelector('.role-status');
        const chatMessages = modal.querySelector('#chat-messages');

        function addChatMessage(author, text) {
            const item = document.createElement('p');
            const label = document.createElement('strong');
            label.textContent = `${author} : `;
            item.append(label, document.createTextNode(text));
            chatMessages.appendChild(item);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function updateRole() {
            const role = rolePicker.value;
            const canCreate = role === 'Collaborateur' || role === 'Admin';
            const canResolve = role === 'Technicien' || role === 'Admin';
            ticketTitle.disabled = !canCreate;
            ticketForm.querySelector('button').disabled = !canCreate;
            modal.querySelectorAll('.resolve-ticket').forEach(button => { button.disabled = !canResolve || button.dataset.resolved === 'true'; });
            roleStatus.textContent = role === 'Admin' ? 'Admin : création et résolution des tickets autorisées.' : role === 'Technicien' ? 'Technicien : résolution des tickets autorisée.' : 'Collaborateur : création de tickets autorisée.';
        }

        ticketForm.addEventListener('submit', event => {
            event.preventDefault();
            if (rolePicker.value !== 'Collaborateur' && rolePicker.value !== 'Admin') return;
            const item = document.createElement('li');
            const createdTitle = ticketTitle.value.trim();
            const summary = document.createElement('span');
            item.dataset.title = createdTitle;
            item.dataset.remaining = String(remainingSeconds);
            summary.className = 'ticket-summary';
            summary.textContent = `${createdTitle} · Ouvert · SLA ${formatTime(remainingSeconds)}`;
            const resolveButton = document.createElement('button');
            resolveButton.type = 'button';
            resolveButton.className = 'resolve-ticket';
            resolveButton.textContent = 'Résoudre';
            resolveButton.disabled = rolePicker.value !== 'Admin';
            resolveButton.addEventListener('click', () => {
                item.dataset.resolved = 'true';
                summary.textContent = `${createdTitle} · Résolu`;
                resolveButton.textContent = 'Résolu';
                resolveButton.dataset.resolved = 'true';
                resolveButton.disabled = true;
            });
            item.append(summary, resolveButton);
            ticketList.prepend(item);
            ticketForm.reset();
        });
        rolePicker.addEventListener('change', updateRole);
        modal.querySelector('.modal-close').addEventListener('click', closeDemo);
        modal.querySelector('#chat-form').addEventListener('submit', event => {
            event.preventDefault();
            const input = modal.querySelector('#chat-input');
            const message = input.value.trim();
            if (!message) return;
            addChatMessage(rolePicker.value, message);
            input.value = '';
            window.setTimeout(() => addChatMessage('ChronosDesk', 'Message reçu, réponse simulée.'), 700);
        });
        updateRole();
    }

    document.querySelectorAll('[data-demo]').forEach(button => button.addEventListener('click', () => openDemo(button.dataset.demo === 'zoo' ? 'zoo' : 'chronos')));
    document.querySelectorAll('.filter-button').forEach(button => button.addEventListener('click', () => {
        const category = button.dataset.filter;
        document.querySelectorAll('.filter-button').forEach(filter => {
            const isActive = filter === button;
            filter.classList.toggle('is-active', isActive);
            filter.setAttribute('aria-pressed', String(isActive));
        });
        document.querySelectorAll('.project-card[data-category]').forEach(card => {
            card.classList.toggle('is-hidden', category !== 'all' && card.dataset.category !== category);
        });
    }));
    modal.querySelector('.modal-close').addEventListener('click', closeDemo);
    modal.addEventListener('click', event => { if (event.target === modal) closeDemo(); });
    const currentYear = document.getElementById('current-year');
    if (currentYear) currentYear.textContent = String(new Date().getFullYear());
    document.addEventListener('keydown', event => {
        if (modal.getAttribute('aria-hidden') === 'true') return;
        if (event.key === 'Escape') {
            closeDemo();
            return;
        }
        if (event.key !== 'Tab') return;
        const focusable = [...modal.querySelectorAll('a[href], button:not(:disabled), input:not(:disabled), select:not(:disabled), textarea:not(:disabled), [tabindex]:not([tabindex="-1"])')]
            .filter(element => element.getClientRects().length > 0);
        if (!focusable.length) return;
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });

    window.setInterval(() => {
        if (modal.style.display !== 'block') return;
        const display = modal.querySelector('#sla-display');
        if (!display) return;
        remainingSeconds = Math.max(0, remainingSeconds - 1);
        display.textContent = formatTime(remainingSeconds);
        modal.querySelectorAll('#demo-tickets li:not([data-resolved="true"])').forEach(item => {
            item.dataset.remaining = String(Math.max(0, Number(item.dataset.remaining) - 1));
            item.querySelector('.ticket-summary').textContent = `${item.dataset.title} · Ouvert · SLA ${formatTime(Number(item.dataset.remaining))}`;
        });
    }, 1000);
});