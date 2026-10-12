document.addEventListener('DOMContentLoaded', () => {
    const storageKey = 'stock-pulse-inventory-v1';
    const initialInventory = [
        { name: 'Ordinateurs portables', quantity: 24 },
        { name: 'Écrans 24 pouces', quantity: 38 },
        { name: 'Stations d’accueil', quantity: 16 },
        { name: 'Claviers', quantity: 52 }
    ];
    const list = document.getElementById('inventoryList');
    const selector = document.getElementById('product-select');
    const quantityInput = document.getElementById('movement-quantity');
    const status = document.getElementById('stock-status');
    const chartElement = document.getElementById('stockChart');
    const chartStatus = document.getElementById('chart-status');
    let inventory = initialInventory.map(product => ({ ...product }));
    let chart;
    let storageAvailable = true;

    function isValidInventory(value) {
        return Array.isArray(value)
            && value.length === initialInventory.length
            && value.every((product, index) =>
                product
                && product.name === initialInventory[index].name
                && Number.isSafeInteger(product.quantity)
                && product.quantity >= 0
            );
    }

    function loadInventory() {
        let savedInventory;
        try {
            savedInventory = localStorage.getItem(storageKey);
        } catch (error) {
            storageAvailable = false;
            status.textContent = 'La sauvegarde locale est indisponible. Les changements resteront temporaires.';
            return;
        }
        if (savedInventory === null) return;
        let parsedInventory;
        try {
            parsedInventory = JSON.parse(savedInventory);
        } catch (error) {
            status.textContent = 'La sauvegarde est illisible. Les valeurs d’exemple sont chargées.';
            return;
        }
        if (!isValidInventory(parsedInventory)) {
            status.textContent = 'Les données enregistrées sont invalides. Les valeurs d’exemple sont chargées.';
            return;
        }
        inventory = parsedInventory;
        status.textContent = 'Inventaire restauré depuis ce navigateur.';
    }

    function saveInventory() {
        if (!storageAvailable) return;
        try {
            localStorage.setItem(storageKey, JSON.stringify(inventory));
        } catch (error) {
            storageAvailable = false;
            status.textContent = 'Impossible de sauvegarder dans ce navigateur. Les changements restent en mémoire jusqu’à la fermeture.';
        }
    }

    function renderInventory() {
        const selectedProduct = selector.value;
        list.replaceChildren();
        selector.replaceChildren();

        inventory.forEach((product, index) => {
            const item = document.createElement('li');
            item.textContent = `${product.name} : ${product.quantity} unités`;
            list.appendChild(item);

            const option = document.createElement('option');
            option.value = String(index);
            option.textContent = product.name;
            selector.appendChild(option);
        });

        if (selectedProduct !== '') selector.value = selectedProduct;
        document.getElementById('product-count').textContent = String(inventory.length);
        document.getElementById('unit-count').textContent = String(inventory.reduce((total, product) => total + product.quantity, 0));
        document.getElementById('low-stock-count').textContent = String(inventory.filter(product => product.quantity <= 10).length);

        if (chart) {
            chart.data.labels = inventory.map(product => product.name);
            chart.data.datasets[0].data = inventory.map(product => product.quantity);
            chart.update();
        }
    }

    function initializeChart() {
        if (!window.Chart) {
            chartStatus.textContent = 'Graphique indisponible : Chart.js ne s’est pas chargé. La liste et les mouvements restent utilisables.';
            return;
        }
        chart = new Chart(chartElement, {
            type: 'bar',
            data: {
                labels: inventory.map(product => product.name),
                datasets: [{ label: 'Unités en stock', data: inventory.map(product => product.quantity), backgroundColor: ['#74a7ff', '#63d5c2', '#9a87ed', '#edbd72'], borderRadius: 4 }]
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
        const amount = Number(quantityInput.value);
        if (!Number.isInteger(index) || !inventory[index]) {
            status.textContent = 'Sélectionnez un produit valide.';
            return;
        }
        if (!Number.isSafeInteger(amount) || amount < 1) {
            status.textContent = 'Saisissez une quantité entière supérieure à zéro.';
            quantityInput.focus();
            return;
        }
        if (direction < 0 && inventory[index].quantity < amount) {
            status.textContent = 'Stock insuffisant pour cette sortie.';
            quantityInput.focus();
            return;
        }
        const updatedQuantity = inventory[index].quantity + direction * amount;
        if (!Number.isSafeInteger(updatedQuantity)) {
            status.textContent = 'La quantité demandée dépasse la limite autorisée.';
            return;
        }
        inventory[index].quantity = updatedQuantity;
        saveInventory();
        renderInventory();
        if (storageAvailable) {
            status.textContent = `${direction > 0 ? 'Entrée' : 'Sortie'} de ${amount} unité(s) enregistrée.`;
        }
    }

    document.getElementById('stock-in').addEventListener('click', () => moveStock(1));
    document.getElementById('stock-out').addEventListener('click', () => moveStock(-1));
    document.getElementById('reset-stock').addEventListener('click', () => {
        if (!window.confirm('Réinitialiser l’inventaire de démonstration ?')) return;
        inventory = initialInventory.map(product => ({ ...product }));
        saveInventory();
        renderInventory();
        if (storageAvailable) status.textContent = 'Inventaire de démonstration réinitialisé.';
    });

    document.getElementById('current-year').textContent = String(new Date().getFullYear());
    loadInventory();
    renderInventory();
    initializeChart();
    if (status.textContent === 'Données de démonstration chargées.') {
        status.textContent = 'Mode démo : données locales, aucun serveur requis.';
    }
});
