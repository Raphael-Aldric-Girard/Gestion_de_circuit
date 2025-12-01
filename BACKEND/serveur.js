 const PORT = 3000;
const express = require('express');
const mysql = require('mysql');
const app = express();
const cors = require('cors');

app.use(cors());
app.use(express.json());

const connection = mysql.createConnection({
    host: '172.16.194.254',
    user: ' rGirard',
    password: 'B0Af2rz@jsIFbKXE',
    database: 'SpeedCircuit'
});

connection.connect(err=>{
    if (err) console.log(err);
    console.log('Connecté à la base mysql');
});

app.listen(PORT, () => {
    console.log(`Serveur backend opérationnel : http://172.16.194.254:${PORT}`);
});

function getCookie(name) {
  // Ajoute "=" pour chercher "nomCookie=valeur"
  const nameEQ = name + "=";
  
  // Sépare tous les cookies (séparés par "; ")
  const cookies = document.cookie.split(';');
  
  for (let i = 0; i < cookies.length; i++) {
    let cookie = cookies[i];
    
    // Supprime les espaces au début
    while (cookie.charAt(0) === ' ') {
      cookie = cookie.substring(1);
    }
    
    // Si le cookie correspond, retourne sa valeur
    if (cookie.indexOf(nameEQ) === 0) {
      return cookie.substring(nameEQ.length);
    }
  }
  
  return null; // Cookie non trouvé
}

app.get('/compte', (req, res) => {
    let query = 'SELECT E.Mail, E.Nom, C.Prenom, E.Identifiant FROM Client C, Entite E WHERE E.IdEntite = C.IdEntite AND E.IdEntite = ?';
    let idEntite = getCookie('user_name');

    connection.query(query, [idEntite], (err, results) => {
        if (err) {
            console.error('Erreur SQL:', err);
            return res.status(500).json({ message : 'Erreur interne au serveur' });
        }

        res.json(results);

    });
});

app.get('/vehicule/voiture', (req, res) => {
    const query = `
      SELECT IdVehicule, Marque, Modele
      FROM Vehicule
      WHERE IdVehicule IN (SELECT IdVehicule FROM Voiture);
    `;
    connection.query(query, (err, results) => {
        if (err) {
            console.error('Erreur SQL:', err);
            return res.status(500).json({ message: 'Erreur interne au serveur' });
        }
        res.json(results);
    });
});