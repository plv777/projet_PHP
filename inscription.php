<?php
session_start();
if (isset($_SESSION['user'])){
    require 'alreadyConnected.php';
    die();
}
require "recap_valid.php" ;

if (isset($_POST["name"]) && isset($_POST["firstName"]) &&
    isset($_POST["password"]) && isset($_POST["validation"]) && isset($_POST["email"])) {
// print_r($_POST) ;
    if (!preg_match("#^[a-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'\-\s]{2,60}$#i",$_POST["name"])) {
        $error_messages[] = "Le nom n'est pas valide !" ;
    }
    if (!preg_match("#^[a-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'\-\s]{2,60}$#i",$_POST["firstName"])) {
        $error_messages[] = "Le prénom n'est pas valide !" ;
    }
    if (!preg_match("#^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'\-\s&@*$!?]{4,60}$#i",$_POST["password"])) {
        $error_messages[] = "Le mot de passe n'est pas valide !" ;
    }
    if ($_POST["validation"] != $_POST["password"]) {
        $error_messages[] = "Les deux mots de passe ne correspondent pas !" ;
    }
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Le format du courriel n'est pas valide !" ;
    }
    if (!recaptcha_valid($_POST["g-recaptcha-response"], $_SERVER["REMOTE_ADDR"])) {
        $error_messages[] = "Captcha invalide !" ;
    }
    
    if (!isset($error_messages)) {
        require "bdd.php";
        $verifyEmail=$bdd->prepare('SELECT email FROM users WHERE email = ?');
        $verifyEmail->execute(array($_POST['email']));

        if(!empty($verifyEmail->fetch())){
            $error_messages[]='email déjà utilisé !';
        }else{
            $newUser=$bdd->prepare('INSERT INTO users(name,firstname,password,email,token) VALUES(?,?,?,?,?)');
            $newUser->execute(array($_POST['name'],$_POST['firstName'],password_hash($_POST['password'],PASSWORD_BCRYPT),$_POST['email'],""));
            if($newUser->rowCount() > 0){
                $success=true;
            }
            $newUser->closeCursor();
        }
        $verifyEmail->closeCursor();

    }
}  
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
    <script src='https://www.google.com/recaptcha/api.js'></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="style.css" rel="stylesheet">
        <title>INSCRIPTION</title>
    </head>
    <header>
    <?php include "menu.php" ; ?>
    </header>

    <body>
        <form action="inscription.php" method="POST">
        <div class="g-recaptcha" data-sitekey="6LdUQFcUAAAAAG44mLEKfU4ONHqNP5UDSxfROYfn"></div>
            Nom<input name="name" type="text">
            Prénom<input name="firstName" type="text">
            Mot de passe<input name="password" type="text">
            Confirmation<input name="validation" type="text">
            Courriel<input name="email" type="text">
            <input type="submit">
        </form>
        <?php
    if (isset($error_messages)) {
        foreach($error_messages as $message) {
            echo "<p style='color:red'>" . $message ."</p>" ;
        }
    }
    if (isset($success)) {
        echo "<p style='color:green'>Formulaire bien rempli et vous êtes bien inscrit !</p>" ;
    }

?>


    </body>
</html>



