console.log("A JavaScript fájl betöltődött!");

document.addEventListener("DOMContentLoaded", function() {
    document.querySelector(".input-submit").addEventListener("click", function(e) {
        e.preventDefault();

        // Form adatainak kinyerése
        const nev = document.getElementById("nev").value.trim();
        const email = document.getElementById("email").value.trim();
        const username = document.getElementById("user").value.trim();
        const password = document.getElementById("pass").value.trim();

        // Ellenőrzés, hogy a kötelező mezők ki vannak-e töltve
        if (!nev || !email || !username || !password) {
            alert("Kérjük, töltse ki az összes mezőt.");
            return;
        }

        const userData = {
            username: username,
            password: password,
            email: email,
            nev: nev
        };

        console.log("Elküldött adatok:", userData);

        // AJAX kéréssel elküldjük a backendnek
        fetch("http://localhost/vizsgarem/src/controller.php?action=addUsers", {
            method: "POST",
            body: JSON.stringify(userData),
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => {
            console.log("Válasz státusz:", response.status);
            if (!response.ok) {
                throw new Error("HTTP hiba: " + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log("Szerver válasza:", data);
            alert(data.message);
        })
        .catch(error => console.error("Hiba:", error));
    });
});
fetch("controller.php?action=getUsers")
    .then(response => response.json())
    .then(users => {
        const tableBody = document.querySelector("#user-table tbody");
        tableBody.innerHTML = ""; // Töröljük a korábbi tartalmat

        users.forEach(user => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${user.nev || "N/A"}</td>
                <td>${user.email || "N/A"}</td>
                <td>${user.username}</td>
                <td>${user.password}</td>
                <td>${user.image_count || 0}</td>
                <td>${user.is_locked == 1 ? "Igen" : "Nem"}</td>
                <td><button class="input-submit" onclick="lockUser(${user.id})">Letiltás</button></td>
                <td><button class="input-submit" onclick="deleteUser(${user.id})">Törlés</button></td>
            `;

            tableBody.appendChild(row);
        });
    })
    .catch(error => console.error("Hiba a felhasználók lekérésekor:", error));

function lockUser(id) {
    fetch("controller.php?action=lockUser", {
        method: "POST",
        body: JSON.stringify({ id: id }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}

function deleteUser(id) {
    if (!confirm("Biztosan törölni szeretnéd ezt a felhasználót?")) return;

    fetch("controller.php?action=deleteUser", {
        method: "POST",
        body: JSON.stringify({ id: id }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}