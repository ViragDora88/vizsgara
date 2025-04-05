<?php

class controller {

    private $users;

    public function __construct($login_model) {
        $this->users = $login_model;  // A login_model példány átadása
    }

    // Felhasználók lekérése
    public function getUsers() {
        $users = $this->users->getUsers();  // A login_model-ban található getUsers metódus meghívása
        echo json_encode($users);  // Az eredmény JSON formátumban történő visszaküldése
    }

    // Felhasználó hozzáadása
    public function addUsers() {
        $data = json_decode(file_get_contents("php://input"));  // A POST kérés adatainak beolvasása

        // Felhasználó hozzáadása
        $this->users->addUser($data->nev, $data->pass);  
        
        echo json_encode([
            "message" => "Felhasználó létrehozva"  // Sikeres válasz
        ]);
    }
    public function login() {
    $data = json_decode(file_get_contents("php://input"));  // A POST adat beolvasása
    $username = $data->username;
    $password = $data->password;
    
    $user = $this->users->login($username, $password);  // A login_model login metódusa

    if ($user) {
        if($user === "Admin" && $password ==="admin87"){
            session_start();
            $_SESSION['admin'] = true; //Session változó beálítása
            echo json_encode(["message" => "Sikeres bejelentkezés"]);
            http_response_code(200);
        }else{
            echo json_encode(["message" => "Hibás felhasználónév vagy jelszó"]);
            http_response_code(401);
        }
    }
    }
}
?>




