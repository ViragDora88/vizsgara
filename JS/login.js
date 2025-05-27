
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const username = document.getElementById("user").value;
    const password = document.getElementById("pass").value;
    
    debugger;
    fetch("../src/controller.php?action=login", {
        method: "POST",
        credentials: "include",
        body: JSON.stringify({
            
            username: username,
            password: password
        }),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => {
        // Mindig próbáljuk meg feldolgozni a JSON választ
        return response.json().then(data => {
            if (!response.ok) {
                // Ha a válasz nem OK, dobjunk hibát a szerver üzenetével
                throw new Error(data.message || "Ismeretlen hiba történt.");
            }
            return data; // Ha OK, visszaadjuk a JSON adatokat
        });
    })
    .then(data => {
        console.log(data);

        // Ellenőrizzük, hogy a felhasználó az "Admin" user-e
        if (data.username === "Admin") {
            // Ha admin, akkor admin.html-re irányítjuk a felhasználót
            window.location.href = "../HTML/admin.html";
        } else {
            // Ha nem admin, akkor user.html-re irányítjuk a felhasználót
            window.location.href = "../HTML/users.html";
        }
    })
    .catch(error => {
        // Hibakezelés: hálózati hiba vagy szerver által küldött hibaüzenet
        console.error("Hiba:", error);
        alert(error.message || "Nem sikerült csatlakozni a szerverhez.");
    });
    
});
