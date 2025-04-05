<?php
session_start();  // Session indítása
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Ha nincs bejelentkezett admin, irányítsuk át a login oldalra
    header("Location: login.html");
    exit();
}
