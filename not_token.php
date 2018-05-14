<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet">
    <title>Token inconnu</title>
</head>
<body>
<header>
    <?php include "menu.php" ; ?>
</header>
    <h1>Vous n'avez pas accès à ce compte ! </h1>
    <?php unset($_SESSION["token"]) ; ?>
</body>
</html>