<?php
    try {
        // chaine de connexion à la base de données
        $dsn='mysql:host=localhost;dbname=SpeedCircuit';

        // option de connexion encodage UTF8 pour MySQL
        $options = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];

        // création d'une instance de connexion à la base de données et ouverture de la connexion
        $pdo = new PDO($dsn, 'circuit', 'VQ2kbJaHf0PmItial7SZ', $options);
        
        // choix de la méthode d'information en cas d'erreur levée d'exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo 'connexion effectuée avec le driver ' . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . '<br>';
    }
        catch (PDOException $e) {
        $msg = 'ERREUR PDO dans ' . $e->getFile() . ' : ' . $e->getLine() . ' : ' . $e->getMessage();
        die($msg);
    }
?>