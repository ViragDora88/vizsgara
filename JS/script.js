document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const username = document.getElementById('user').value;
        const password = document.getElementById('pass').value;

        const data = {
            username: username,
            password: password
        };

        fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert(result.message);
                window.location.href = result.redirect || 'admin.php';
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            console.error('Hiba:', error);
            alert('Hiba történt a bejelentkezés során.');
        });
    });
});

