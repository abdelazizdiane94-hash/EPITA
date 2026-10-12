document.addEventListener('DOMContentLoaded', () => {
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
            status.textContent = 'Graphique indisponible : Chart.js ne s’est pas chargé. Vérifiez votre connexion puis rechargez la page.';
            return;
        }
        chart = new Chart(document.getElementById('stockChart'), {
            type: 'bar',
            data: {
                labels: inventory.map(product => product.name),
                datasets: [{ label: 'Unités en stock', data: inventory.map(product => product.quantity), backgroundColor: ['#3b82f6', '#06b6d4', '#10b981', '#f59e0b'], borderRadius: 4 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#d9e4f2' } } },
                scales: {
                    x: { ticks: { color: '#9aa8bb' }, grid: { color: 'rgba(154,168,187,.12)' } },
                    y: { beginAtZero: true, ticks: { color: '#9aa8bb' }, grid: { color: 'rgba(154,168,187,.12)' } }
                }
            }
        });
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
    status.textContent = 'Mode démo : données locales, aucun serveur PHP requis.';
});