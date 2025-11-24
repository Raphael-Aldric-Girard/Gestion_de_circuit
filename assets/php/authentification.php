<?php
    
    require_once('includes/connexion.php');
    
    try{
    // vérification des paramètres POST
        if (!isset($_POST['identifiant']) || !isset($_POST['password'])) {
            echo "Champs d'authentification manquants";
            exit;
        }

        // préparation de la requête
        if (!($stmt = $pdo->prepare('SELECT mdp FROM Entite WHERE Identifiant = :identifiant;'))) {
            echo "Echec de la préparation : (" . $pdo->errorCode() . ") " . implode(", ", $pdo->errorInfo());
        }

        // récupération des paramètres
        $identifiant = $_POST['identifiant'];
        $mdp = $_POST['password'];

        // liaison des paramètres (identifiant en chaîne)
        $stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);

        // exécution de la requête
        $stmt->execute();

        // récupération du mot de passe stocké (une seule lecture)
        $arrColumn = $stmt->fetchColumn();

        if ($arrColumn === false) {
            echo ("Identifiant introuvable");
        } elseif ($mdp === $arrColumn) {
             header('Location: ../html/informationCompte.html');
             exit;
        } else {
            echo ("Échec de la connexion : mot de passe incorrect");
        }

    }
    catch (PDOException $e) {
        $msg = 'erreur lors de la connection à la BDD ou de l\'exécution de la requête : ' . $e->getMessage();
        echo($msg);
    }



?>