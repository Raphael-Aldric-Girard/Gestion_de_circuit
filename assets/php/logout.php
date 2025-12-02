<?php
// Détruit la session et redirige vers la page d'authentification
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Effacer toutes les variables de session
$_SESSION = [];

// Détruire le cookie de session si présent
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Supprimer le cookie personnalisé 'user_name'
if (isset($_COOKIE['user_name'])) {
    setcookie('user_name', '', time() - 3600, '/');
    // Si le cookie a été créé avec des paramètres spécifiques, utilisez les mêmes :
    // setcookie('user_name', '', time() - 3600, '/', 'votre-domaine.com', true, true);
}

// Détruire la session côté serveur
session_destroy();

// Redirection vers la page d'authentification
header('Location: ../html/authentification.html');
exit;
