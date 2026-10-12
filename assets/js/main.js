document.addEventListener('DOMContentLoaded', () => {
    const modal = document.createElement('section');
    modal.className = 'modal';
    modal.id = 'chronos-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-labelledby', 'chronos-title');
    modal.innerHTML = `<div class="modal-content chronos-demo">
        <button class="modal-close" type="button" aria-label="Fermer">&times;</button>
        <h2 id="chronos-title">ChronosDesk | Démonstration</h2>
        <p>Simulation locale, indépendante du serveur PHP.</p>
        <label for="demo-role">Rôle actif</label>
        <select id="demo-role"><option>Collaborateur</option><option>Technicien</option><option>Admin</option></select>
        <p class="role-status" aria-live="polite"></p>
        <form id="ticket-form"><label for="ticket-title">Nouveau ticket</label><input id="ticket-title" maxlength="100" required placeholder="Sujet du ticket"><button type="submit">Créer</button></form>
        <ul id="demo-tickets" aria-live="polite"></ul>
        <p>SLA restant : <strong id="sla-display">05:00</strong></p>
        <section class="demo-chat"><h3>Chat asynchrone simulé</h3><div id="chat-messages" aria-live="polite"></div><form id="chat-form"><input id="chat-input" maxlength="300" required placeholder="Votre message"><button type="submit">Envoyer</button></form></section>
    </div>`;
    document.body.appendChild(modal);

    const rolePicker = modal.querySelector('#demo-role');
    const ticketForm = modal.querySelector('#ticket-form');
    const ticketTitle = modal.querySelector('#ticket-title');
    const ticketList = modal.querySelector('#demo-tickets');
    const roleStatus = modal.querySelector('.role-status');
    const chatMessages = modal.querySelector('#chat-messages');
    let remainingSeconds = 300;

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

    function formatTime(seconds) {
        return `${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;
    }

    document.querySelectorAll('[data-demo="chronos-desk"]').forEach(button => button.addEventListener('click', () => {
        modal.style.display = 'block';
        remainingSeconds = 300;
        modal.querySelector('#sla-display').textContent = formatTime(remainingSeconds);
        updateRole();
        modal.querySelector('.modal-close').focus();
    }));
    modal.querySelector('.modal-close').addEventListener('click', () => { modal.style.display = 'none'; });
    modal.addEventListener('click', event => { if (event.target === modal) modal.style.display = 'none'; });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') modal.style.display = 'none'; });
    rolePicker.addEventListener('change', updateRole);

    ticketForm.addEventListener('submit', event => {
        event.preventDefault();
        if (rolePicker.value !== 'Collaborateur' && rolePicker.value !== 'Admin') return;
        const item = document.createElement('li');
        const createdTitle = ticketTitle.value.trim();
        const summary = document.createElement('span');
        summary.textContent = `${createdTitle} · Ouvert · SLA ${formatTime(remainingSeconds)}`;
        const resolveButton = document.createElement('button');
        resolveButton.type = 'button';
        resolveButton.className = 'resolve-ticket';
        resolveButton.textContent = 'Résoudre';
        resolveButton.disabled = rolePicker.value !== 'Admin';
        resolveButton.addEventListener('click', () => {
            summary.textContent = `${createdTitle} · Résolu`;
            resolveButton.dataset.resolved = 'true';
            resolveButton.textContent = 'Résolu';
            resolveButton.disabled = true;
        });
        item.append(summary, resolveButton);
        ticketList.prepend(item);
        ticketForm.reset();
    });

    modal.querySelector('#chat-form').addEventListener('submit', event => {
        event.preventDefault();
        const input = modal.querySelector('#chat-input');
        const message = input.value.trim();
        if (!message) return;
        addChatMessage(rolePicker.value, message);
        input.value = '';
        window.setTimeout(() => addChatMessage('ChronosDesk', 'Message reçu, réponse simulée.'), 700);
    });

    window.setInterval(() => {
        if (modal.style.display !== 'block') return;
        if (remainingSeconds > 0) remainingSeconds -= 1;
        modal.querySelector('#sla-display').textContent = formatTime(remainingSeconds);
    }, 1000);
});