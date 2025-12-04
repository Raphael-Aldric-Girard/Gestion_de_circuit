<?php
    // Connexion à la base de données
    require_once('includes/connexion.php');

    // Récupération des variables nécessaires
    $id = htmlspecialchars($_POST['identifiant']);
    $nom = htmlspecialchars($_POST['nom']);
    $mdp = htmlspecialchars($_POST['mdp']);
    $mail = htmlspecialchars($_POST['mail']);
    $confirmMdp = htmlspecialchars($_POST['confirm_mdp']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $age = htmlspecialchars($_POST['age']);

    
    // Vérification si le mot de passe de confirmation correspond au mot de passe
    if($confirmMdp !== $mdp){
        header("Location: ../html/erreur/erreurMdp.html");
        exit(); 
    }

    // Vérification de l'email AVANT l'insertion
    $sql = "SELECT COUNT(*) as nb FROM `Entite` WHERE `Mail` = :mail";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result['nb'] > 0){
        header("Location: ../html/erreur/erreurInscription.html");
        exit(); 
    }

    // Création de la requête d'inscription
    $sql = "INSERT INTO `Entite`(`Identifiant`, `Nom`, `mdp`, `Mail`) VALUES (:id, :nom, :mdp, :mail)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->execute();

    // ✅ Récupération de l'ID inséré directement
    $idEntite = $pdo->lastInsertId();

    // Enregistrement du client dans la table client
    $sql = "INSERT INTO `Client`(`Prenom`, `Age`, `IdEntite`) VALUES (:prenom, :age, :idEntite)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':idEntite', $idEntite, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: ../html/informationCompte.html');
    exit(); 
?>