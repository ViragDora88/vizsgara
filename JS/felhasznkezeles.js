console.log("A JavaScript fájl betöltődött!");

document.addEventListener("DOMContentLoaded", function () {
    // Form eseménykezelő
    const form = document.getElementById("user-form"); // Győződj meg róla, hogy az űrlapnak van id-ja

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Megakadályozzuk az alapértelmezett űrlap beküldést

            // Form adatainak kinyerése
            const nev = document.getElementById("nev")?.value.trim();
            const email = document.getElementById("email")?.value.trim();
            const username = document.getElementById("user")?.value.trim();
            const password = document.getElementById("pass")?.value.trim();

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
    }

    // Felhasználók lekérése
    fetch("http://localhost/vizsgarem/src/controller.php?action=getUsers")
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP hiba: " + response.status);
            }
            return response.json();
        })
        .then(users => {
            console.log("Felhasználók:", users);
            // További kód a felhasználók megjelenítésére
        })
        .catch(error => console.error("Hiba a felhasználók lekérésekor:", error));
});

// Felhasználó zárolása
function lockUser(id) {
    fetch("http://localhost/vizsgarem/src/controller.php?action=lockUser", {
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

    fetch("http://localhost/vizsgarem/src/controller.php?action=deleteUser", {
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