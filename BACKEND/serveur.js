const PORT = 5000;
const express = require('express');
const mysql = require('mysql');
const app = express();
const cors = require('cors');

app.use(express.json());

// Ou utilisez le package cors
app.use(cors({
    origin: 'http://172.16.194.254', // L'origine de votre page
    credentials: true  // ← IMPORTANT !
}));

const connection = mysql.createPool({
    host: '127.0.0.1',
    user: 'rGirard',
    password: 'B0Af2rz@jsIFbKXE',
    database: 'SpeedCircuit'

});

function parseCookies(cookieHeader) {
    const cookies = {};
    if (cookieHeader) {
        cookieHeader.split(';').forEach(cookie => {
            const parts = cookie.trim().split('=');
            if (parts.length === 2) {  // Vérification ajoutée
                cookies[parts[0]] = parts[1];
            }
        });
    }
    return cookies;
}

app.listen(PORT, () => {
    console.log(`Serveur backend opérationnel : http://172.16.194.254:${PORT}`);
});

app.get('/compte', (req, res) => {
    console.log('Route /compte appelée');
    console.log('Headers cookie:', req.headers.cookie); // Voir tous les cookies

    const cookies = parseCookies(req.headers.cookie);
    console.log('Cookies parsés:', cookies); // Voir le résultat du parsing
    console.log('Noms des cookies disponibles:', Object.keys(cookies));

    let idEntite = cookies.user_name;
    console.log(`idEntite : ${idEntite}`);

    // Vérifiez si idEntite existe
    if (!idEntite) {
        return res.status(401).json({ message: 'Non authentifié - cookie user_name manquant' });
    }
    let query = 'SELECT E.Mail, E.Nom, C.Prenom, E.Identifiant FROM Client C, Entite E WHERE E.IdEntite = C.IdEntite AND E.IdEntite = ?';

    connection.query(query, [idEntite], (err, results) => {
        if (err) {
            console.error('Erreur SQL:', err);
            return res.status(500).json({ message: 'Erreur interne au serveur' });
        }
        console.log(results);
        res.json(results);
    });
});

app.get('/reservation', (req, res) => {
    console.log('Route /reservation appelée');
    const cookie = parseCookies(req.headers.cookie);

    let idEntite = cookie.user_name;
    console.log(`idEntite : ${idEntite}`);

    if (!idEntite) {
        return res.status(401).json({ message: 'Non authentifié - cookie user_name manquant' });
    }

    let query = 'SELECT S.DateSession, V.Marque, V.Modele FROM Client C INNER JOIN Reservation R ON C.IdClient = R.IdClient INNER JOIN Session S ON R.IdSession = S.IdSession INNER JOIN Vehicule V ON R.IdVehicule = V.IdVehicule WHERE C.IdEntite = ? AND S.DateSession > NOW();'

    connection.query(query, [idEntite], (err, results) => {
        if (err) {
            console.error('Erreur SQL:', err);
            return res.status(500).json({ message: 'Erreur interne au serveur' });
        }
        console.log(results);
        res.json(results);
    });
});

app.get('/reservation/past', (req, res) => {
    console.log('Route /reservation/past appelée');
    const cookie = parseCookies(req.headers.cookie);

    let idEntite = cookie.user_name;
    console.log(`idEntite : ${idEntite}`);

    let query = 'SELECT S.DateSession, V.Marque, V.Modele FROM Client C INNER JOIN Reservation R ON C.IdClient = R.IdClient INNER JOIN Session S ON R.IdSession = S.IdSession INNER JOIN Vehicule V ON R.IdVehicule = V.IdVehicule WHERE C.IdEntite = ? AND S.DateSession < NOW();'

    connection.query(query, [idEntite], (err, results) => {
        if (err) {
            console.error('Erreur SQL : ', err);
            return res.status(500).json({ message: 'Erreur interne au serveur' });
        }
        console.log(results);
        res.json(results);
    });
});

app.get('/vehicule', (req, res) => {
    const query = `
      SELECT IdVehicule, Marque, Modele
      FROM Vehicule;
    `;
    connection.query(query, (err, results) => {
        if (err) {
            console.error('Erreur SQL:', err);
            return res.status(500).json({ message: 'Erreur interne au serveur' });
        }
        res.json(results);
    });
});

app.get('/evenement', (req, res) => {
    const query =
        'SELECT LibelleEvenement, DateEvenement, Prix FROM Evenement;'
        ;
    connection.query(query, (err, results) => {
        if (err) {
            console.error('Erreur SQL:', err);
            return res.status(500).json({ message: 'Erreur interne au serveur' });
        }
        res.json(results);
    });
});

app.post('/login', (req, res) => {
    const { identifiant, mdp } = req.body;

    if (!identifiant || !mdp) {
        return res.status(400).json({ message: 'Identifiant et mot de passe requis.' });
    }

    // Une seule requête avec JOIN au lieu de 3
    const query = `
        SELECT e.IdEntite, p.prenom, p.IdPoste 
        FROM Entite e
        LEFT JOIN Personnel p ON e.IdEntite = p.IdEntite
        WHERE e.Identifiant = ? AND e.mdp = ?
    `;
    
    connection.query(query, [identifiant, mdp], (err, results) => {
        if (err) {
            console.error('Erreur SQL :', err);
            return res.status(500).json({ message: 'Erreur interne au serveur' });
        }

        if (results.length === 0) {
            return res.status(401).json({ message: 'Identifiant ou mot de passe incorrect' });
        }

        if (!results[0].prenom) {
            return res.status(401).json({ message: 'Vous n\'avez pas de compte pour accéder à cette application' });
        }

        res.json({ 
            user: { 
                prenom: results[0].prenom, 
                Poste: results[0].IdPoste 
            }
        });
    });
});