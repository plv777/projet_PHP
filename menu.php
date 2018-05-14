<nav>
            <a href="index.php">ACCUEIL</a>
<?php
if (isset($_SESSION['user'])) { ?>
    <a href="deconnexion.php">DÉCONNEXION</a>
    <a href="profil.php">PROFIL</a>
<?php
}    
else {
?>
            <a href="inscription.php">INSCRIPTION</a>
            <a href="connexion.php">CONNEXION</a>
<?php
}
?>
            <a href="lost_password.php">MdP perdu</a>

</nav>
