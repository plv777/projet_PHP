<?php
session_start();

if(isset($_SESSION['user'])){
    require 'alreadyConnected.php';
    die();
}

?>