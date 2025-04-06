<?php

// PHP hibák megjelenítése fejlesztési környezetben
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// login_model és db osztályok betöltése
require_once __DIR__ . '/../src/login_model.php';
require_once __DIR__ . '/../src/db.php';


class Controller {

    private $users;

    public function __construct($login_model) {
        $this->users = $login_model;
    }

    public function login() {
        header('Content-Type: application/json'); // JSON válasz fejléc

        $data = json_decode(file_get_contents("php://input"));

        if (!$data || !isset($data->username) || !isset($data->password)) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Hiányzó adatok"]);
            return;
        }

        $username = $data->username;
        $password = $data->password;

        $user = $this->users->login($username, $password);

        if ($user) {
            if (isset($user['locked']) && $user['locked']) {
                http_response_code(403); // Forbidden
                echo json_encode(["message" => "A felhasználó le van tiltva."]);
                return;
            }
        
            session_start();
            $_SESSION['user'] = $user['username']; // Session változó beállítása
            http_response_code(200); // OK
            echo json_encode(["message" => "Sikeres bejelentkezés"]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(["message" => "Hibás felhasználónév vagy jelszó"]);
        }
    }

    public function getUsers() {
        header('Content-Type: application/json'); // JSON válasz fejléc
        try {
            $users = $this->users->getUsers(); // Felhasználók lekérése az adatbázisból
            echo json_encode($users); // JSON formátumban visszaküldjük az adatokat
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Hiba történt a felhasználók lekérésekor: " . $e->getMessage()]);
        }
    }

    public function addUsers() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));
    
        // Ellenőrzés hiányzó adatokra
        if (!$data || !isset($data->nev) || !isset($data->username) || !isset($data->password) || !isset($data->email)) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Hiányzó adatok"]);
            return;
        }
    
        try {
            if ($this->users->userExists($data->username)) {
                http_response_code(409); // Conflict
                echo json_encode(["message" => "A felhasználónév már létezik"]);
                return;
            }
            // Felhasználó hozzáadása
            $this->users->addUser($data->nev, $data->username, $data->password, $data->email);
            http_response_code(201); // Created
            echo json_encode(["message" => "Felhasználó létrehozva"]);
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Hiba történt: " . $e->getMessage()]);
        }
    }
    // A felhasználó törlése
    public function deleteUser() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));
    
        if (!isset($data->id)) {
            http_response_code(400);
            echo json_encode(["message" => "Hiányzó felhasználó ID"]);
            return;
        }
    
        $result = $this->users->deleteUserById($data->id);
    
        if ($result) {
            echo json_encode(["message" => "Felhasználó törölve."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Nem sikerült törölni a felhasználót."]);
        }
    }
    // A felhasználó letiltása
    public function lockUser() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));
    
        if (!isset($data->id)) {
            http_response_code(400);
            echo json_encode(["message" => "Hiányzó felhasználó ID"]);
            return;
        }
    
        $result = $this->users->lockUserById($data->id);
    
        if ($result) {
            echo json_encode(["message" => "Felhasználó letiltva."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Nem sikerült letiltani a felhasználót."]);
        }
    }
}

if (isset($_GET['action'])) {
    $db = new Db();
    $login_model = new login_model($db);
    $controller = new Controller($login_model);

    switch ($_GET['action']) {
        case 'login':
            $controller->login();
            break;
        case 'getUsers':
            $controller->getUsers();
            break;
        case 'addUsers':
            $controller->addUsers();
            break;
        case 'deleteUser':
            $controller->deleteUser();
            break;
        case 'lockUser':
            $controller->lockUser();
            break;
        
        case 'logout':
            session_start();
            session_destroy(); // Munkamenet törlése
            header("Location: http://localhost/vizsgarem/HTML/login.html"); // Átirányítás a bejelentkezési oldalra
            exit();
            break;

        default:
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Ismeretlen művelet"]);
            break;
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Nincs megadva művelet"]);
}


?>