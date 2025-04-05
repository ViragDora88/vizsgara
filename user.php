<?php 
session_start();
include 'config.php';
include 'connect.php';


// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: /login.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/style/style.css"> 
    </head>
    <body>
        <div>Szia! Jó nézelődést! :)</div>
            <?php           
            if(isset($_SESSION['user'])){
                $User=$_SESSION['user'];
                $query=mysqli_query($conn, "SELECT users. * FROM  'users' "
                        . "WHERE users.nev='$Nev'");
                while($row=mysqli_fetch_array($query)){
                    echo $row['Név'];
                }
            }
            ?>
            <div class="back-home">
            <span><a href="/logout.php">Kijelentkezés</a></span>
        </div>
    </body>
</html>

