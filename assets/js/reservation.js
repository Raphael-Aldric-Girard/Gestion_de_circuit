/**
 * Script de gestion de la réservation - Charge et affiche les voitures disponibles
 */

// Configuration
const API_URL = 'http://172.16.194.254:3000/vehicule/voiture'; // API Node backend
const FALLBACK_API_URL = '../php/reservation.php'; // API PHP fallback

let selectedCarId = null;
let selectedCarName = null;

// Au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    loadVehicles();
});

/**
 * Charge les véhicules depuis l'API
 */
async function loadVehicles() {
    const grilleVoitures = document.getElementById('grilleVoitures');
    const compteur = document.getElementById('compteurVoitures');
    
    try {
        // Essayer d'abord l'API Node
        let response = await fetch(API_URL);
        let data = response.json();
        
        // Si l'API Node échoue, utiliser le fallback PHP
        if (!response.ok) {
            response = await fetch(FALLBACK_API_URL);
            data = await response.json();
        } else {
            data = await data;
        }
        
        // Vérifier si les données sont valides
        if (!Array.isArray(data)) {
            throw new Error('Format de données invalide');
        }
        
        // Afficher les véhicules
        displayVehicles(data);
        compteur.textContent = `${data.length} voiture${data.length > 1 ? 's' : ''}`;
        
    } catch (error) {
        console.error('Erreur lors du chargement des véhicules:', error);
        grilleVoitures.innerHTML = `<p style="color: red; grid-column: 1/-1;">Erreur de chargement. Veuillez rafraîchir la page.</p>`;
        compteur.textContent = '0 voiture';
    }
}

/**
 * Affiche les véhicules dans la grille
 */
function displayVehicles(vehicles) {
    const grilleVoitures = document.getElementById('grilleVoitures');
    grilleVoitures.innerHTML = ''; // Effacer le contenu précédent
    
    if (vehicles.length === 0) {
        grilleVoitures.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">Aucune voiture disponible</p>';
        return;
    }
    
    // Générer les cartes de véhicules
    vehicles.forEach((vehicle, index) => {
        const card = createCarCard(vehicle, index);
        grilleVoitures.appendChild(card);
    });
}

/**
 * Crée une carte de véhicule
 */
function createCarCard(vehicle, index) {
    const card = document.createElement('div');
    card.className = 'carCard';
    card.style.cursor = 'pointer';
    
    // Image par défaut si l'API Node est utilisée (elle ne retourne pas d'images)
    const defaultImages = [
        'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?q=80&w=400',
        'https://images.unsplash.com/photo-1580273916550-e323be2ed5d6?q=80&w=400',
        'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?q=80&w=400',
        'https://images.unsplash.com/photo-1563720223185-11003d516935?q=80&w=400',
        'https://images.unsplash.com/photo-1503376763036-066120622c74?q=80&w=400',
        'https://images.unsplash.com/photo-1600712242805-5f78671b24da?q=80&w=400'
    ];
    const image = vehicle.image || defaultImages[index % defaultImages.length];
    
    card.innerHTML = `
        <div class="carImageWrapper">
            <img src="${image}" alt="${vehicle.Marque} ${vehicle.Modele}" class="carImage">
        </div>
        <div class="carDetails">
            <div class="carName">${vehicle.Marque} ${vehicle.Modele}</div>
            <div class="carSpecs">
                <span class="availabilityTag statusDispo">Disponible</span>
            </div>
        </div>
    `;
    
    // Ajouter l'événement de sélection
    card.addEventListener('click', () => selectVehicle(vehicle.IdVehicule, vehicle.Marque, vehicle.Modele));
    
    return card;
}

/**
 * Sélectionne un véhicule et met à jour le formulaire
 */
function selectVehicle(id, marque, modele) {
    selectedCarId = id;
    selectedCarName = `${marque} ${modele}`;
    
    // Mettre à jour le champ du formulaire
    const inputField = document.getElementById('selectedCar');
    if (inputField) {
        inputField.value = selectedCarName;
    }
    
    console.log(`Véhicule sélectionné: ${selectedCarName} (ID: ${id})`);
}
