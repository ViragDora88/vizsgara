document.querySelector(".input-submit").addEventListener("click", function(e) {
    e.preventDefault();

    // Form adatainak kinyerése
    const nev = document.getElementById("nev").value;
    const email = document.getElementById("email").value;
    const user = document.getElementById("user").value;
    const pass = document.getElementById("pass").value;

    const userData = {
        username: user,
        password: pass,
        email: email
    };

    // AJAX kéréssel elküldjük a backendnek
    fetch("controller.php?action=addUser", {
        method: "POST",
        body: JSON.stringify(userData),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => alert(data.message))
    .catch(error => console.error("Hiba:", error));
});
