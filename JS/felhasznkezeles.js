//console.log("A JavaScript fájl betöltődött!");

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM betöltődött!");
    // Form eseménykezelő
    const form = document.getElementById("user-form"); // Győződj meg róla, hogy az űrlapnak van id-ja
   console.log("Űrlap elem:", form);
    // Ellenőrizzük, hogy az űrlap elem létezik-e
    if (form) {
        form.addEventListener("submit", function (e) {
            console.log("Eseménykezelő aktiválva!");
            e.preventDefault(); // Megakadályozzuk az alapértelmezett beküldést
            console.log("Űrlap beküldve!");
            // Form adatainak kinyerése
            const nev = document.getElementById("nev")?.value.trim();
            console.log("Név:", nev);
            const email = document.getElementById("email")?.value.trim();
            console.log("Email:", email);
            const username = document.getElementById("user")?.value.trim();
            console.log("Felhasználónév:", username);
            const password = document.getElementById("pass")?.value.trim();
            console.log("Jelszó:", password);

            // Ellenőrzés, hogy a kötelező mezők ki vannak-e töltve
            if (!nev || !email || !username || !password) {
                alert("Kérjük, töltse ki az összes mezőt.");
                return;
            }

            const userData = {
                nev: nev,
                email: email,
                username: username,
                password: password
            };

            console.log("Fetch hívás indul:", userData);

            // AJAX kéréssel elküldjük a backendnek
            fetch("../src/controller.php?action=addUsers", {
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
        
    }

    // Felhasználók lekérése
    fetch("../src/controller.php?action=getUsers")
    .then(response => {
        if (!response.ok) {
            throw new Error("HTTP hiba: " + response.status);
        }
        return response.json();
    })
    .then(users => {
        const userTableBody = document.querySelector("#user-table tbody");
        userTableBody.innerHTML = ""; // Töröljük a meglévő sorokat

        if (users.length > 0) {
            users.forEach(user => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${user.nev}</td>
                    <td>${user.email}</td>
                    <td>${user.username}</td>
                    <td>${user.password}</td>
                    <td>${user.image_count}</td>
                    <td>${user.is_locked ? "Igen" : "Nem"}</td>
                    <td>
                        <button onclick="lockUser(${user.id})">Letiltás</button>
                        <button onclick="deleteUser(${user.id})">Törlés</button>
                    </td>
                `;
                userTableBody.appendChild(row);
            });
        } else {
            userTableBody.innerHTML = '<tr><td colspan="7">Nincs megjeleníthető adat.</td></tr>';
        }
    })
    .catch(error => console.error("Hiba a felhasználók lekérésekor:", error))
});



// Felhasználó zárolása
function lockUser(id) {
    fetch("../src/controller.php?action=lockUser", {
        method: "POST",
        body: JSON.stringify({ id: id }),
        headers: { "Content-Type": "application/json" }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP hiba: " + response.status);
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => console.error("Hiba a felhasználó zárolásakor:", error));
}

// Felhasználó törlése
function deleteUser(id) {
    if (!confirm("Biztosan törölni szeretnéd ezt a felhasználót?")) return;

    fetch("../src/controller.php?action=deleteUser", {
        method: "POST",
        body: JSON.stringify({ id: id }),
        headers: { "Content-Type": "application/json" }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP hiba: " + response.status);
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => console.error("Hiba a felhasználó törlésekor:", error));
}