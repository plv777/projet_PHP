<?php
session_start();

if(!isset($_SESSION['user'])){
    require 'not_connected.php';
    die();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet">
    <title>profil</title>
</head>
<body>
<header>
    <?php include "menu.php" ; ?>
</header>  
<ul>
    <li>Nom: <?= htmlspecialchars($_SESSION['user']['name'])?></li>
    <li>Prénom: <?= htmlspecialchars($_SESSION['user']['firstName']) ?></li>
    <li>Email: <?= htmlspecialchars($_SESSION['user']['email'])?></li>
    <li>Connexion le: <?= htmlspecialchars (date ('d-m-Y à H:i:s', $_SESSION['user']['timeConnection']))?></li>
</ul>
    
</body>
</html>

    


