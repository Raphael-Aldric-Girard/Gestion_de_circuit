<?php
    require('../js/redirectionInfoUser.js');
    require_once('includes/connexion.php');
    
    try{
    // vérification des paramètres POST
        if (!isset($_POST['identifiant']) || !isset($_POST['password'])) {
            echo "Champs d'authentification manquants";
            exit;
        }

        // préparation de la requête
        if (!($stmt = $pdo->prepare('SELECT mdp, IdEntite FROM Entite WHERE Identifiant = :identifiant;'))) {
            echo "Echec de la préparation : (" . $pdo->errorCode() . ") " . implode(", ", $pdo->errorInfo());
        }

        // récupération des paramètres
        $identifiant = htmlspecialchars($_POST['identifiant'], ENT_QUOTES, 'UTF-8');
        $mdp_user = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

        // liaison des paramètres (identifiant en chaîne)
        $stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);

        // exécution de la requête
        $stmt->execute();

        // récupération du mot de passe stocké (une seule lecture)
        $arrColumn = $stmt->fetchall();
        $mdp = $arrColumn[0][0];
        var_dump($arrColumn);
        echo $mdp;
        if ($arrColumn === false) {
            echo ("Identifiant introuvable");
        } elseif ($mdp_user === $mdp) {

            $id = $arrColumn[0][1];
            session_start();
            // creation de cookie de connection
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $id;
            setcookie('user_name', $id, time() + (24 * 60 * 60), '/');
            header('Location: ../html/informationCompte.html');
        
            exit;
        } else {
            echo ("Échec de la connexion : mot de passe incorrect");
            header('Location: ../html/erreurIdMdp.html');
        }

    }
    catch (PDOException $e) {
        $msg = 'erreur lors de la connection à la BDD ou de l\'exécution de la requête : ' . $e->getMessage();
        echo($msg);
    }



?>