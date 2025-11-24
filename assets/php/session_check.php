<?php
// Fichier utilitaire pour vérifier si l'utilisateur est connecté
// Inclure en haut des pages PHP protégées : require_once 'session_check.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier la variable de session définie à l'authentification
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // redirection vers la page d'authentification
    header('Location: ../html/authentification.html');
    exit;
}

?>
