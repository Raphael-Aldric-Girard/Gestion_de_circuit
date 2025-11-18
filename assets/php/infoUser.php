<?php

//connexion
include 'includes/connexion.php';

try{
    // preparation de la requete
    if (!($stmt = $pdo->prepare("SELECT * FROM Client WHERE id_Client = :id_user")) 
        ) {
        echo "Echec de la préparation : (" . $pdo->errorCode() . ") " . implode(", ", $pdo->errorInfo());
    }

    // récupération du paramètre id_user
    $id_user = $_POST['id_user'];

    // liaison des paramètres
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

    // execution de la requete
    $stmt->execute();
}
catch (PDOException $e) {
    $msg = 'erreur lors de la connection à la BDD ou de l\'exécution de la requête : ' . $e->getMessage();
    echo($msg);
}

?>
