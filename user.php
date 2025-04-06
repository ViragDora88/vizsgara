<?php
session_start();  // Session indítása
if (!isset($_SESSION['users']) || $_SESSION['users'] !== true) {
    // Ha nincs bejelentkezett admin, irányítsuk át a login oldalra
    header("Location: login.html");
    exit();
}
