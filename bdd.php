<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8','root','');
} catch (Exception $e) {
    die($e->getMessage()) ;
}
