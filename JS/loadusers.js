//felhasználók betöltése a legördülő listába
document.addEventListener("DOMContentLoaded", function() {
    fetch('../src/controller.php?action=getUsers')
        .then(response => response.json())
        .then(users => {
            const select = document.getElementById("user_id");
            users.forEach(user => {
                const option = document.createElement("option");
                option.value = user.id;
                option.textContent = user.nev;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Hiba a felhasználók betöltésekor:', error));
});