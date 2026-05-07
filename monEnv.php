<?php

//  Variables de connexion
$_ENV['dbHost'] = '172.16.20.14'; 
$_ENV['dbName'] = 'bs243097'; 
$_ENV['dbUser'] = 'bs243097'; 
$_ENV['dbPasswd'] = '20243097';

//  Fonction de connexion
function connexion() {
    $strConnex = "host=" . $_ENV['dbHost'] .
                 " dbname=" . $_ENV['dbName'] .
                 " user=" . $_ENV['dbUser'] .
                 " password=" . $_ENV['dbPasswd'];

    $ptrDB = pg_connect($strConnex);
   if (!$ptrDB) {
        die("Erreur : impossible de se connecter à la base étudiante");
    }

    return $ptrDB;
}
?>
