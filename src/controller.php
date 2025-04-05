<?php

// PHP hibák megjelenítése fejlesztési környezetben
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// login_model és db osztályok betöltése
require_once __DIR__ . '/login_model.php';
require_once __DIR__ . '/db.php';

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
        header('Content-Type: application/json');
        $users = $this->users->getUsers();
        echo json_encode($users);
    }

    public function addUsers() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));

        if (!$data || !isset($data->nev) || !isset($data->pass)) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Hiányzó adatok"]);
            return;
        }

        $this->users->addUser($data->nev, $data->pass);

        http_response_code(201); // Created
        echo json_encode(["message" => "Felhasználó létrehozva"]);
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