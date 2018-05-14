<?php
session_start();
if (!isset($_SESSION['user'])){
    require 'not_connected.php';
    die();
}

unset($_SESSION['user']); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet">
    <title>Déconnexion</title>
</head>
<body>
<header>
    <?php include "menu.php" ; ?>
    
</header>
    <h1>Vous êtes bien deconnecté !</h1>
</body>
</html>
