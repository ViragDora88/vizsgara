document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const username = document.getElementById("user").value;
    const password = document.getElementById("pass").value;

    fetch("http://localhost/vizsgarem/src/controller.php?action=login", {
        method: "POST",
        body: JSON.stringify({
            username: username,
            password: password
        }),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("HTTP hiba: " + response.status);
        }
        return response.text(); // Ideiglenesen textként olvassuk be
    })
    .then(text => {
        try {
            const data = JSON.parse(text); // JSON feldolgozás
            console.log(data);
            if (data.message === "Sikeres bejelentkezés") {
                window.location.href = "http://localhost/vizsgarem/HTML/admin.html";
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error("JSON feldolgozási hiba:", error);
            console.error("Kapott válasz:", text);
            alert("Hiba történt a szerver válaszának feldolgozása során.");
        }
    })
    .catch(error => {
        console.error("Hiba:", error);
        alert("Nem sikerült csatlakozni a szerverhez.");
    });
});