<?php
session_start();
if (isset($_POST["email"])) {

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $error_messages[] = "Le format du courriel n'est pas valide !" ;
}

if (!isset($error_messages)) {
    require "bdd.php";
    $response=$bdd->prepare('SELECT * FROM users WHERE email = ?');
    $response->execute(array($_POST['email']));
    $userInfos = $response->fetch(PDO::FETCH_ASSOC);

    if(empty($userInfos)){
        $error_messages[]='Cet email n\'existe pas !';
    }else{
//envoyer un email avec Php
        $token = md5(rand()*time()) ;

        $new_token=$bdd->prepare("UPDATE users SET token = ? WHERE email = ?") ;
        $new_token->execute(array($token,$_POST["email"])) ;
        if ($new_token->rowCount() == 0) {
            $error_messages[]='Erreur de mise à jour !';
        }
        else {
            $mail = $userInfos['email'] ;		// Destinataire

            $crlf = "\r\n";
            $url = "http://localhost/projet_php/new_password.php" ;

            $message_txt = "Veuillez aller sur le lien suivant" . $url . "?token=" . $token ;	// Message en text
            $message_html = "<html><head></head><body><strong>
                Veuillez cliquer sur le lien suivant : <a href='$url?token=$token'></strong></body></html>";	// message en HTML

            $sujet = "Mot de passe perdu";	// Sujet
            $header = "From: \"Noreply\"no-reply@gmail.com>".$crlf;	// Nom, prénom et email de l'expediteur
            $header.= "Reply-to: \"Contact\" contact@gmail.com>".$crlf;	// Nom, prénom et email de la personne en retour de mail

            $boundary = "-----=".md5(rand());
            $header.= "MIME-Version: 1.0".$crlf;
            $header.= "Content-Type: multipart/alternative;".$crlf." boundary=\"$boundary\"".$crlf;
            $message = $crlf."--".$boundary.$crlf;
            $message.= "Content-Type: text/plain; charset=\"UTF-8\"".$crlf;
            $message.= "Content-Transfer-Encoding: 8bit".$crlf;
            $message.= $crlf.$message_txt.$crlf;
            $message.= $crlf."--".$boundary.$crlf;
            $message.= "Content-Type: text/html; charset=\"UTF-8\"".$crlf;
            $message.= "Content-Transfer-Encoding: 8bit".$crlf;
            $message.= $crlf.$message_html.$crlf;
            $message.= $crlf."--".$boundary."--".$crlf;
            $message.= $crlf."--".$boundary."--".$crlf;
            mail($mail,$sujet,$message,$header);        
            $_SESSION["email"] = $_POST["email"] ;
            $success=true;
            //    'email' => $userInfos['email'],
        }
    }
    $response->closeCursor();

}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet">
    <title>Mot de passe perdu</title>
</head>
<body>
    <header>
        <?php include "menu.php" ; ?>
    </header>
    <form action="lost_password.php" method="POST">
        <label for="email">Tapez votre email</label>
        <input type="text" id="email" name="email" placeholder="ex Pierre@hotmail.com">
        <input type="submit" value="Envoyer">
    </form>
<?php
    if (isset($error_messages)) {
        foreach($error_messages as $message) {
            echo "<p style='color:red'>" . $message ."</p>" ;
        }
    }
    if (isset($success)) {
        echo "<p style='color:green'>Veuillez consulter votre messagerie !</p>" ;
        // var_dump($_SESSION['user']);
    }
?>

</body>
</html>