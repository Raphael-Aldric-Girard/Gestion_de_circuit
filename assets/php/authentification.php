<?php
    // require('../js/redirectionInfoUser.js'); // ❌ À supprimer
    require_once('includes/connexion.php');
    include_once('../../vendor/autoload.php');

    Sentry\Init(['dsn' => 'http://ab62b5fb0837424aa4b3a9290c4daa6a@172.16.0.100:8000/1']);
    
    // Démarrer la session dès le début
    session_start();
    
    try {
        // Vérification des paramètres POST
        if (!isset($_POST['identifiant']) || !isset($_POST['password'])) {
            die("Champs d'authentification manquants");
        }

        // Récupération des paramètres
        $identifiant = htmlspecialchars($_POST['identifiant'], ENT_QUOTES, 'UTF-8');
        $mdp_user = $_POST['password']; // ✅ Ne PAS utiliser htmlspecialchars sur le mot de passe !

        // Préparation et exécution de la requête
        $stmt = $pdo->prepare('SELECT mdp, IdEntite FROM Entite WHERE Identifiant = :identifiant');
        $stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        $stmt->execute();

        // Récupération du résultat
        $arrColumn = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérification si l'utilisateur existe
        if (empty($arrColumn)) { // ✅ Bon test pour tableau vide
            die("Identifiant introuvable");
        }

        $mdp = $arrColumn[0]['mdp'];
        $id = $arrColumn[0]['IdEntite'];

        // Comparaison du mot de passe
        if ($mdp_user === $mdp) { // ✅ Utiliser === pour comparaison stricte
            // Création de la session
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $id;
            setcookie('user_name', $id, time() + (24 * 60 * 60), '/');
            header('Location: ../html/informationCompte.html');
            throw new Exception("Gestion de circuit : "+$identifiant+" est connecté !");
            exit;
        } else {
            die("Échec de la connexion : mot de passe incorrect");
        }

    } catch (PDOException $e) {
        error_log('Erreur BDD : ' . $e->getMessage());
        die('Erreur lors de la connexion à la BDD');
    }
?>