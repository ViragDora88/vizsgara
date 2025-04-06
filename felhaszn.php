<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználók kezelése</title>
    <link rel="stylesheet" href="http://localhost/vizsgarem/style/felhasznkezeles.css">
</head>
<body>
    <!-- Oldalsó menü -->
    <div class="sidebar">
        <h2>Admin Menü</h2>
        <ul>
            <li><a href="admin.html">Főoldal</a></li>
            <li><a href="felhasznkezeles.html">Felhasználók kezelése</a></li>
            <li><a href="menukez.html">Menük kezelése</a></li>
            <li><a href="#">Beállítások</a></li>
            <li><a href="index.html">Kijelentkezés</a></li>
        </ul>
    </div>

    <!-- Tartalom -->
    <div class="content">
        <div class="login-container">
            <div class="login_title">
                <span>Felhasználók kezelése</span>
            </div>

            <!-- Új felhasználó hozzáadása -->
            <div class="login_add">
                <span>Új Felhasználó hozzáadása</span>
                <br><br>
                <form id="user-form">
                    <div class="input-wrapper">
                        <label for="nev" class="input-label">Név:</label>
                        <input type="text" id="nev" name="nev" class="input-field" required>
                    </div>
                    <br>
                    <div class="input-wrapper">
                        <label for="email" class="input-label">E-mail:</label>
                        <input type="email" id="email" name="email" class="input-field" required>
                    </div>
                    <br>
                    <div class="input-wrapper">
                        <label for="user" class="input-label">Felhasználó név:</label>
                        <input type="text" id="user" name="username" class="input-field" required>
                    </div>
                    <br>
                    <div class="input-wrapper">
                        <label for="pass" class="input-label">Jelszó:</label>
                        <input type="password" id="pass" name="password" class="input-field" required>
                    </div>
                    <br>
                    <div class="input-wrapper">
                        <button type="submit" class="input-submit">Felhasználó hozzáadása</button>
                    </div>
                </form>
            </div>
            <br>

            <!-- Felhasználók listája -->
            <div class="login_show">
                <span>Felhasználók a honlapon</span>
                <br><br>
                <table id="user-table">
                    <thead>
                        <tr>
                            <th>Név:</th>
                            <th>E-mail:</th>
                            <th>Felhasználó név:</th>
                            <th>Jelszó:</th>
                            <th>Képek feltöltése</th>
                            <th>Korlátozás</th>
                            <th>Törlés</th>
                        </tr>
                    </thead>
                   
                    <tbody>
                    <?php
            require_once __DIR__ .'/src/controller.php'; // Hivatkozás a controller.php fájlra
            

            if (!empty($users)) {
                foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['nev']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['password']) ?></td>
                        <td><?= htmlspecialchars($user['image_count']) ?></td>
                        <td><?= $user['is_locked'] ? 'Igen' : 'Nem' ?></td>
                        <td>
                            <button onclick="lockUser(<?= $user['id'] ?>)">Letiltás</button>
                            <button onclick="deleteUser(<?= $user['id'] ?>)">Törlés</button>
                        </td>
                    </tr>
                <?php endforeach;
            } else {
                echo '<tr><td colspan="7">Nincs megjeleníthető adat.</td></tr>';
            }
            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="http://localhost/vizsgarem/JS/felhasznkezeles.js"></script>
</body>
</html>