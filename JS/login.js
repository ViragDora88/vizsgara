document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    // A bejelentkezési űrlap adatainak kinyerése
    const username = document.getElementById("user").value;
    const password = document.getElementById("pass").value;

    // A bejelentkezési adatok objektumba
    const loginData = {
        username: username,
        password: password
    };
//console.log("URL: controller.php?action=login"); // Ellenőrizzük, hogy a helyes URL-t küldjük
    // AJAX kérés a bejelentkezéshez
    fetch("/src/controller.php?action=login", {
        method: "POST",
        body: JSON.stringify(loginData),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === "Sikeres bejelentkezés") {
            // Ha sikeres, írányítsa át az admin.html oldalra
            window.location.href = "http://localhost/vizsgarem/HTML/admin.html";
        } else {
            // Hibás bejelentkezés esetén
            alert(data.message);
        }
    })
    .catch(error => {
        console.error("Hiba:", error);
    });
});
