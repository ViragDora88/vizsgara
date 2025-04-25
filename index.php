<?php
spl_autoload_register(function($class){
    require( __DIR__ . "/src/$class.php");
});



// Osztályok betöltése
require_once 'src/db.php';         // A Db osztály betöltése
require_once 'src/login_model.php'; // A login_model osztály betöltése
require_once 'src/controller.php';  // A controller osztály betöltése

// Adatbázis kapcsolódás
$db = new Db(); // Db osztály példányosítása

// login_model osztály példányosítása a Db példányával
$login_model = new login_model($db);

// Controller osztály példányosítása a login_model példányával
$controller = new controller($login_model);

// Request URL feldolgozása
//$request = explode("/", $_SERVER["REQUEST_URI"]);
//$endpoint = isset($request[2]) ? $request[2] : '';  // A kérés második része az endpoint
$request = explode("/", $_SERVER["REQUEST_URI"]);
$keres = $request[2]; // Végpont lekérése
$keres_tomb = explode("?", $keres);
$vegpont = $keres_tomb[0];  // Az első paraméter (pl. 'login')

switch ($vegpont) {
    case "login":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
            http_response_code(200);
        }
        break;

    case "logout":
        session_start();
        session_destroy(); // Munkamenet törlése
        header("Location: ../HTML/login.html"); // Átirányítás a bejelentkezési oldalra
        exit();
        break;
    
        case "felhasznkezeles":
            require_once 'felhaszn.php';
            break;

    default:
        echo json_encode(["message" => "Invalid endpoint"]);
        http_response_code(404);
        break;
}
// Különböző műveletek végrehajtása az endpoint alapján
//switch ($endpoint) {
//    case "get":
//        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//            $controller->getUsers();  // Felhasználók lekérése
//        }
//        break;
//
//    case "add":
//        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            $controller->addUsers();  // Felhasználó hozzáadása
//        }
//        break;
//
//    default:
//        echo json_encode(["message" => "Invalid endpoint"]);  // Hibás endpoint
//        break;
//}

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vizsgaremek</title>
    <link rel="stylesheet" href="style/style.css">
    
</head>

<body>
    
    <header>

        <nav class ="navbar">
            <div logo><img src="logo" alt="Logo" class="logo"></div>
            <ul class ="menu">
                <li><a href="#home">Kezdőlap</a></li>
                <li class="submenu-wrapper">
                    <a href="#gallery">Galéria<span>  </span></a>
                    <ul class="submenu">
                        <li><a href="#">Esküvői pillanatok színek nélkül</a></li>
                        <li><a href="#">Esküvők</a></li>
                        <li><a href="#">Jegyes fotózások</a></li>
                        <li><a href="#">Akikkel dolgozom</a></li>
                        <li><a href="#">Baba és pocak fotózások</a></li>
                        <li><a href="#">Jártamban-Keltemben</a></li>
                    </ul>
                </li>
                <li class="submenu-wrapper">
                    <a href="#magamrol">Magamról<span>  </span></a>
                    <ul class="submenu">
                        <li><a href="#">Bemutatkozás</a></li>
                        <li><a href="#">Elérhetőségek</a></li>
                        <li><a href="#">Kiállított képeim</a></li>
                    </ul>
                </li>
                <li class="submenu-wrapper">
                <a href="#rendezvenyek">Rendezvények<span>  </span></a>
                    <ul class="submenu">
                        <li><a href="#">PTE</a></li>
                        <li><a href="#">Beremend Beach Party</a></li>
                        <li><a href="#">Siklósi Sárkányos Lovagrend</a></li>
                        <li><a href="#">Beremend Fenyő koncert</a></li>
                    </ul>
                </li>
                <li><a href="HTML/login.html">Bejelentkezés</a></li>
            </ul>
        </nav> 
    </header>
    <main>
        
        <div class="burokkocka">
        <div class="kocka">
            <div class="oldal elol"></div>
            <div class="oldal hatul"></div>
            <div class="oldal jobb"></div>
            <div class="oldal bal"></div>
            <div class="oldal fent"></div>
            <div class="oldal lent"></div>
        </div>
    </div>
    </main>
    <footer>
        <pre><a href="https://www.facebook.com/Photo.Csucsi"><img src="facebooklogo.png" alt="facebooklogo" class="kapcslogo"></a> <a href="https://www.instagram.com/csucsi.gergely/"><img src="instagramlogo.png" alt="instagramlogo" class="kapcslogo"></a> </pre>
        <p>© Csucsi Gergely 2016</p>
    </footer>
    
    
</body>
</html>