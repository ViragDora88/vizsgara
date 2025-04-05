<?php

session_start();

// A session megsemmisítése
session_unset();

// A session törlése
session_destroy();

// Visszairányítás a főoldalra
header("Location: /index.php");
exit();