// app.js for StockPulse client-side application

// Function to fetch inventory data from the API
async function fetchInventory() {
    try {
        const response = await fetch('http://localhost/stock-pulse/api/inventory'); // Adjust the URL as needed
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        displayInventory(data);
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
    }
}

// Function to display inventory data on the dashboard
function displayInventory(data) {
    const inventoryList = document.getElementById('inventory-list');
    inventoryList.innerHTML = ''; // Clear existing content

    data.forEach(item => {
        const listItem = document.createElement('li');
        listItem.textContent = `${item.product_name} - ${item.quantity} in stock`;
        inventoryList.appendChild(listItem);
    });
}

// Event listener for DOMContentLoaded to fetch inventory data
document.addEventListener('DOMContentLoaded', () => {
    fetchInventory();
});