<?php
// --- LOGIQUE MÉTIER (BACKEND) ---

// Configuration
$apiUrl = 'http://172.16.194.254:3000/vehicule/voiture';

// Images par défaut pour l'affichage
$defaultImages = [
    'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?q=80&w=400',
    'https://images.unsplash.com/photo-1580273916550-e323be2ed5d6?q=80&w=400',
    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?q=80&w=400',
    'https://images.unsplash.com/photo-1563720223185-11003d516935?q=80&w=400',
    'https://images.unsplash.com/photo-1503376763036-066120622c74?q=80&w=400',
    'https://images.unsplash.com/photo-1600712242805-5f78671b24da?q=80&w=400'
];

// Initialisation des variables
$voitures = [];
$sourceData = "API Node";
$erreurApi = false;

// 1. Récupération des données (Appel API)
// L'opérateur @ masque les warnings si le serveur est éteint
$json = @file_get_contents($apiUrl);

// 2. Traitement de la réponse
if ($json === FALSE) {
    // Cas d'erreur : API inaccessible
    $erreurApi = true;
    $sourceData = "Mode Démo (Erreur API)";
    
    // Données de secours 
    $voitures = [
        ['IdVehicule' => 1, 'Marque' => 'Audi', 'Modele' => 'R8 V10 (Démo)'],
        ['IdVehicule' => 2, 'Marque' => 'Porsche', 'Modele' => '911 GT3 (Démo)'],
        ['IdVehicule' => 3, 'Marque' => 'Ferrari', 'Modele' => '488 Pista (Démo)'],
        ['IdVehicule' => 4, 'Marque' => 'Lamborghini', 'Modele' => 'Huracan (Démo)']
    ];
} else {
    // Cas succès : Décodage du JSON
    $voitures = json_decode($json, true);
    
    // Sécurité si le JSON est malformé
    if (!is_array($voitures)) {
        $voitures = [];
    }
}

// 3. Fonctions utilitaires pour la Vue
function getImageByIndex($index, $images) {
    return $images[$index % count($images)];
}
?>