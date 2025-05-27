document.addEventListener('DOMContentLoaded', () => {
    fetchOrders();
});

function fetchOrders() {
    fetch('../get_orders.php', {
        credentials: 'include', // Szükséges a hitelesítéshez)
    })
       .then(response => response.text()) // <- először textként olvassuk ki
    .then(text => {
        console.log('Szerver válasza:', text); // ide írd ki
        const data = JSON.parse(text); // itt próbáljuk meg csak utána parse-olni
        fillOrdersAsCards(data);
    })
    .catch(error => {
        console.error('Hiba történt:', error);
    });
}

function fillOrdersAsCards(orders) {
    const container = document.getElementById('orders-container');
    container.innerHTML = '';

    if (!orders.length) {
        container.innerHTML = '<p>Nincs rendelés.</p>';
        return;
    }

    // Csoportosítjuk a rendeléseket felhasználónként
    const grouped = {};
    orders.forEach(order => {
        const username = order.username || `ID: ${order.user_id}`;
        if (!grouped[username]) {
            grouped[username] = [];
        }
        grouped[username].push(order);
    });

    // Card létrehozása felhasználónként
    for (const [username, userOrders] of Object.entries(grouped)) {
        const card = document.createElement('div');
        card.className = 'card';

        const title = document.createElement('h4');
        title.textContent = `Rendelések - ${username}`;
        card.appendChild(title);

        const table = document.createElement('table');
        table.innerHTML = `
            <thead>
                <tr>
                    <th>Rendelés ID</th>
                    <th>Kép neve</th>
                    <th>Rendelés dátuma</th>
                </tr>
            </thead>
            <tbody>
                ${userOrders.map(orders => `
                    <tr>
                        <td>${orders.id}</td>
                        <td>${orders.image_name || order.image_id}</td>
                        <td>${orders.ordered_at}</td>
                    </tr>
                `).join('')}
            </tbody>
        `;

        card.appendChild(table);
        container.appendChild(card);
    }
}