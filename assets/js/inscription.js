document.getElementById('formInscription').addEventListener('submit', function (event) {
    event.preventDefault();

    const form = event.target;
    
    // Configuration du backend (adaptable selon l'environnement)
    const backendUrl = 'http://localhost:3000';
    const endpoint = backendUrl + '/inscription';

    // Collect form data into an object
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });

    console.log('Données envoyées:', data); // Pour déboguer

    // Disable submit to avoid double submission
    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    fetch(endpoint, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Statut de la réponse:', response.status); // Pour déboguer
        
        if (response.ok) {
            // Parse la réponse JSON même en cas de succès
            return response.json().then(result => {
                console.log('Inscription réussie:', result);
                // Redirect on success
                window.location.href = '../assets/html/informationCompte.html';
            });
        } else {
            // Non-OK: parse JSON error
            return response.json().then(err => {
                throw err;
            }).catch(parseError => {
                // Si le JSON ne peut pas être parsé, créer un objet d'erreur
                throw { message: `Erreur HTTP ${response.status}` };
            });
        }
    })
    .catch(err => {
        console.error('Erreur inscription :', err);
        const msg = (err && err.message) ? err.message : 'Erreur lors de l\'inscription.';
        alert(msg);
        
        // Re-enable le bouton en cas d'erreur
        if (submitBtn) submitBtn.disabled = false;
    });
});