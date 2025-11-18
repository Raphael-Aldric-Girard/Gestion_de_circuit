<?php
    require_once('includes/connexion.php');

    $id = trim($_POST['identifiant'] ?? "");
    $mdp = trim($_POST['mdp'] ?? "");

    $sql = "INSERT INTO Entité (Pseudo, mdp) VALUES (:id, :mdp);";
    echo $sql;
    $stmt = $pdo -> prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $id, PDO::PARAM_STR);

    $stmt -> execute();

?>