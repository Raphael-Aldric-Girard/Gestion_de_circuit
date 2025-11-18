<?php
    
    require_once('includes/connexion.php');
    
    try{
    // preparation de la requete
        if (!($stmt = $pdo->prepare('Select mdp FROM Entite where Identifiant = :identifiant;'))) 
            {
                echo "Echec de la préparation : (" . $pdo->errorCode() . ") " . implode(", ", $pdo->errorInfo());
            }

        // récupération des paramètres 
        $identifiant = $_POST['identifiant'];
        $mdp = $_POST['password'];

        // liaison des paramètres
        $stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_INT);

        // execution de la requete
        $stmt->execute();
        
        echo $identifiant;
        echo"<br>";
        echo $mdp;
        echo"<br>";
        echo $stmt->fetchColumn();
        echo"<br>";
        $arrColumn = $stmt->fetchColumn();
        
        if($mdp === $arrColumn){
            echo ("Connection reussi");
        }
        else{
            echo ("Echec de la connection");
        }

    }
    catch (PDOException $e) {
        $msg = 'erreur lors de la connection à la BDD ou de l\'exécution de la requête : ' . $e->getMessage();
        echo($msg);
    }



?>