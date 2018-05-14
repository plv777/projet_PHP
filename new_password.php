<?php
session_start();
if (!isset($_SESSION["token"])) {
    $_SESSION["token"] = $_GET["token"] ;
    echo "OK" ;
}    
if (!isset($_SESSION["token"])){
    require 'not_token.php';
    die ();
}
require "bdd.php";
$response=$bdd->prepare('SELECT token FROM users WHERE email = ?');
$response->execute(array($_SESSION['email'])) ;
$userInfos = $response->fetch(PDO::FETCH_ASSOC) ;
if ($_SESSION["token"] != $userInfos['token']) {
    require 'not_token.php';
    die ();
}else{ 

    if (isset($_POST["new_password"]) && isset($_POST["validation"])) {
    // print_r($_POST) ;
        if (!preg_match("#^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'\-\s&@*$!?]{4,60}$#i",$_POST["new_password"])) {
            $error_messages[] = "Le mot de passe n'est pas valide !" ;
        }
        if (!preg_match("#^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'\-\s&@*$!?]{4,60}$#i",$_POST["validation"])) {
            $error_messages[] = "Le mot de passe de confirmation n'est pas valide !" ;
        }
        if ($_POST["new_password"] != $_POST["validation"]) {
            $error_messages[] = "Les deux mots de passe ne sont pas identiques !" ;
        }
        if (!isset($error_messages)) {
            $new_password=$bdd->prepare('UPDATE users SET password = ? WHERE email = ?');
            $new_password->execute(array($_POST["new_password"],$_SESSION['email'])) ;
            if ($new_password->rowCount() == 0) {
                $error_messages[]='Erreur de mise à jour !';    
            }
            $success=true;
            $new_password->closeCursor();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="style.css" rel="stylesheet">
        <title>Nouveau mot de passe</title>
    </head>
    <body>
        <header>
            <?php include "menu.php" ; ?>
        </header>

        <form action="new_password.php" method="POST">
            Tapez votre nouveau mot de passe : <input name="new_password" type="text">
            Confirmation <input name="validation" type="text">
            <input type="submit" value="Envoyer">
        </form>
<?php
    if (isset($error_messages)) {
        foreach($error_messages as $message) {
            echo "<p style='color:red'>" . $message ."</p>" ;
        }
    }
    if (isset($success)) {
        echo "<p style='color:green'>Votre nouveau mot de passe est bien changé !</p>" ;
        unset($_SESSION["token"]) ;
        unset($_SESSION["email"]) ;
    }
?>
    </body>
</html>
