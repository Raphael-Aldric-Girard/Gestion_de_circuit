<?php
    // Connexion à la base de données
    require_once('includes/connexion.php');

    // récupération des variables nécéssaires à la première requete
    $id = $_POST['identifiant'];
    $nom = $_POST['nom'];
    $mdp = $_POST['mdp'];
    $mail = $_POST['mail'];

    // recuperation des variables pour la troisieme et derniere requete
    $prenom = $_POST['prenom'];
    $age = $_POST['age'];

    // création de la requête d'inscription
    $sql = "INSERT INTO `Entite`(`IdEntite`, `Identifiant`, `Nom`, `mdp`, `Mail`) VALUES (NULL,:id, :nom, :mdp, :mail);";

    // préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Liaison des valeurs aux paramètres de la requête
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);

    // exécution de la requête
    $stmt->execute();

    // reinitialisation des variables
    $sql = "";
    $stmt = "";

    // deuxième requête permettant de récuperer l'identifiant du dernier enregistrement
    // pour pouvoir entrer le nouvel utilisateur dans la table client
    $sql = "SELECT IdEntite FROM `Entite` WHERE Identifiant = :id ;";

    // préparation de la deuxième requete
    $stmt = $pdo->prepare($sql);

    // Liaison des valeurs aux paramètres de la deuxième requête
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);

    // execution de la requete
    $stmt->execute();

    // recuperation de la reponse de la requete qui sera utilisé ultérieurement
    $arrAll = $stmt->fetchAll();


    // troisieme requête permettant l'enregistrement du client dans la table client
    $sql = "INSERT INTO `Client`(`IdClient`, `Prenom`, `Age`, `IdEntite`) VALUES (NULL, :prenom, :age, :idEntite);";

    // préparation de la dernière requête
    $stmt = $pdo->prepare($sql);

    var_dump($arrAll);
    
    // liaison des valeurs aux paramètres de la requête
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':idEntite', $arrAll, PDO::PARAM_INT);

    // execution de la requete
    $stmt->execute();


?>