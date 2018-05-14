
<?php
session_start();
if(isset($_SESSION['user'])){
    require 'alreadyConnected.php';
    die();
}
if ( isset($_POST["password"])  && isset($_POST["email"])) {

    if (!preg_match("#^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'\-\s&@*$!?]{4,60}$#i",$_POST["password"])) {
        $error_messages[] = "Le mot de passe n'est pas valide !" ;
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Le format du courriel n'est pas valide !" ;
    }
    if (!isset($error_messages)) {
        require "bdd.php";
        $response=$bdd->prepare('SELECT * FROM users WHERE email = ?');
        $response->execute(array($_POST['email']));
        $userInfos = $response->fetch(PDO::FETCH_ASSOC);

        if(empty($userInfos)){
            $error_messages[]='l\'email n\'existe pas et il faut le créer !';
        }else{
          
            if(password_verify($_POST['password'],$userInfos['password'])){
                $success=true;
                $_SESSION['user'] = array(
                    'name'=> $userInfos['name'],
                    'firstName'=> $userInfos['firstname'],
                    'email' => $userInfos['email'],
                    'timeConnection'=> time(),
                    'connectionIp'=> $_SERVER['REMOTE_ADDR']
                );

            }else{
                $error_messages[]="Mauvais mot de passe !";
            }
           
        }
        $response->closeCursor();

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
    <title>Connexion</title>
</head>
<body>
<header>
    <?php include "menu.php" ; ?>
</header>  
<?php
if (!isset($success)) {
?>
<form action="connexion.php" method="POST">

    <label for="email">Email</label>
    <input type="text" id="email" name="email" placeholder="ex Pierre@hotmail.com">

    <label for="password">mot de passe</label>
    <input type="text" id="password" name="password" placeholder="Mot de Passe">
   
    <input type="submit" value="Connexion">
</form>
<?php
}
    if (isset($error_messages)) {
        foreach($error_messages as $message) {
            echo "<p style='color:red'>" . $message ."</p>" ;
        }
    }
    if (isset($success)) {
        echo "<p style='color:green'>Vous êtes bien connecté!</p>" ;
        // var_dump($_SESSION['user']);
    }

?>

</body>
</html>