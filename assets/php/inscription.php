<?php
    require_once('includes/connexion.php');

    $id = trim($_POST['identifiant'] ?? "");
    $mdp = trim($_POST['mdp'] ?? "");

    $sql = "INSERT INTO Entité (Pseudo, mdp) VALUES (:id, :mdp);";
    $stmt = $pdo -> prepare($sql);

    $stmt->execute([
        ':id' => htmlspecialchars($id),
        ':mdp' => htmlspecialchars($mdp)
    ]);

?>