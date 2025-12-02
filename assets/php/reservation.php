<?php
// --- LOGIQUE MÉTIER (BACKEND) ---

// Configuration

$baseApiUrl = 'http://172.16.194.254:5000/vehicule/';

$typeVehicule = $_GET['type'] ?? $_POST['type'] ?? null;

// 2. Validation et sécurisation du type
$typesValides = ['moto', 'voiture'];
if (!in_array(strtolower($typeVehicule), $typesValides))
    {
        $apiUrl = $baseApiUrl;
    }
    else
    {
        // 3. Construction de l'URL API dynamique
        $apiUrl = $baseApiUrl . strtolower($typeVehicule);
    }


// Initialisation des variables
$voitures = [];
$sourceData = "API Node";
$erreurApi = false;

// 1. Récupération des données (Appel API)
// L'opérateur @ masque les warnings si le serveur est éteint
$json = @file_get_contents($apiUrl);

// Fonction utilitaire
function getImageByIndex($index, $images) {
    return $images[$index % count($images)];
}

// Préparer les données pour JavaScript
$vehData = [];
foreach ($vehicules as $index => $veh) {
    $vehData[] = [
        'IdVehicule' => $veh['IdVehicule'] ?? $index,
        'Marque' => $veh['Marque'] ?? 'Unknown',
        'Modele' => $veh['Modele'] ?? 'Unknown',
        'image' => getImageByIndex($index, $defaultImages),
        'status' => 'Disponible'
    ];
}


// 2. Traitement de la réponse
if ($json === FALSE) {
    // Cas d'erreur : API inaccessible
    $erreurApi = true;
    $sourceData = "Mode Démo (Erreur API)";
    
    // Données de secours 
    /*$voitures = [
        ['IdVehicule' => 1, 'Marque' => 'Audi', 'Modele' => 'R8 V10 (Démo)'],
        ['IdVehicule' => 2, 'Marque' => 'Porsche', 'Modele' => '911 GT3 (Démo)'],
        ['IdVehicule' => 3, 'Marque' => 'Ferrari', 'Modele' => '488 Pista (Démo)'],
        ['IdVehicule' => 4, 'Marque' => 'Lamborghini', 'Modele' => 'Huracan (Démo)']
    ];*/
} else {
    // Cas succès : Décodage du JSON
    $vehicule = json_decode($json, true);
    
    // Sécurité si le JSON est malformé
    if (!is_array($vehicule)) {
        $vehicule = [];
    }
}

// Retourner JSON pour le client
header('Content-Type: application/json');
echo json_encode([
    'success' => !$erreurApi,
    'source' => $sourceData,
    'vehicles' => $vehData
]);
?>                                                            