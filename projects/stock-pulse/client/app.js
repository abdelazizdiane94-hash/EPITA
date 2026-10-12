document.addEventListener('DOMContentLoaded', async () => {
    const inventory = [
        { name: 'Ordinateurs portables', quantity: 24 },
        { name: 'Écrans 24 pouces', quantity: 38 },
        { name: 'Stations d’accueil', quantity: 16 },
        { name: 'Claviers', quantity: 52 }
    ];
    const list = document.getElementById('inventoryList');
    const selector = document.getElementById('product-select');
    const status = document.getElementById('stock-status');
    let chart;

    function renderInventory() {
        list.replaceChildren();
        inventory.forEach((product, index) => {
            const item = document.createElement('li');
            item.textContent = `${product.name} : ${product.quantity} unités`;
            list.appendChild(item);
            if (!selector.options[index]) selector.add(new Option(product.name, String(index)));
        });
        if (chart) {
            chart.data.labels = inventory.map(product => product.name);
            chart.data.datasets[0].data = inventory.map(product => product.quantity);
            chart.update();
        }
    }

    function initializeChart() {
        if (!window.Chart) {
            status.textContent = 'Graphique indisponible : vérifiez la connexion pour charger Chart.js.';
            return;
        }
        chart = new Chart(document.getElementById('stockChart'), {
            type: 'bar',
            data: {
                labels: inventory.map(product => product.name),
                datasets: [{ label: 'Unités en stock', data: inventory.map(product => product.quantity), backgroundColor: ['#e56b4e', '#42a5a0', '#e3b341', '#5f83c7'] }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });
    }

    async function loadApiData() {
        try {
            const response = await fetch('../api/index.php/inventory', { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('API non disponible');
            const result = await response.json();
            if (!Array.isArray(result) || !result.length) throw new Error('Réponse API invalide');
            inventory.splice(0, inventory.length, ...result.map(item => ({ name: item.name, quantity: Number(item.quantity) || 0 })));
            status.textContent = 'Inventaire chargé depuis l’API locale.';
        } catch {
            status.textContent = 'Mode démonstration : données locales utilisées (API PHP non disponible).';
        }
        renderInventory();
    }

    function moveStock(direction) {
        const index = Number(selector.value);
        const amount = Number(document.getElementById('movement-quantity').value);
        if (!Number.isInteger(amount) || amount < 1) {
            status.textContent = 'Saisissez une quantité entière supérieure à zéro.';
            return;
        }
        if (direction < 0 && inventory[index].quantity < amount) {
            status.textContent = 'Stock insuffisant pour cette sortie.';
            return;
        }
        inventory[index].quantity += direction * amount;
        status.textContent = `${direction > 0 ? 'Entrée' : 'Sortie'} de ${amount} unité(s) enregistrée.`;
        renderInventory();
    }

    document.getElementById('stock-in').addEventListener('click', () => moveStock(1));
    document.getElementById('stock-out').addEventListener('click', () => moveStock(-1));
    renderInventory();
    initializeChart();
    await loadApiData();
});