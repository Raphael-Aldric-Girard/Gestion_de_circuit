const express = require('express');
const mysql = require('mysql');
const app = express();
const port = 3000;
const cors = require('cors');
const crypto = require('crypto');

// Configuration CORS plus permissive pour le développement
app.use(cors({
    origin: '*', // En production, spécifiez l'URL exacte de votre frontend
    methods: ['GET', 'POST'],
    credentials: true
}));

// Parse JSON bodies sent by the client
app.use(express.json());
// Parse URL-encoded bodies (optional, useful for form submissions)
app.use(express.urlencoded({ extended: true }));

const connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'rGirard',
    password: 'B0Af2rz@jsIFbKXE',
    database: 'SpeedCircuit'
});

connection.connect(err => {
    if (err) {
        console.error('Erreur de connexion à la DB:', err);
        return;
    }
    console.log('Connecté à la base mysql');
    console.log('');
});

app.post('/login', (req, res) => {
    const { identifiant, password } = req.body;

    if (!identifiant || !password) {
        return res.status(400).json({ message: "Identifiant et mot de passe requis." });
    }

    const hashedPassword = crypto.createHash('sha256').update(password).digest('hex');
    const query = 'SELECT mdp FROM Entite WHERE Identifiant = ?';

    connection.query(query, [identifiant], (err, results) => {
        if (err) {
            console.error('Erreur SQL :', err);
            return res.status(500).json({ message: 'Erreur interne au serveur.' });
        }

        if (results.length === 0) {
            return res.status(401).json({ message: 'Identifiant ou mot de passe incorrect.' });
        }

        // Vérifier que le mot de passe haché correspond
        if (results[0].mdp !== hashedPassword) {
            return res.status(401).json({ message: 'Identifiant ou mot de passe incorrect.' });
        }

        res.json({ message: 'Connection réussie.', user: { id: identifiant } });
    });
});

app.post('/inscription', (req, res) => {
    console.log('Requête reçue sur /inscription:', req.body);
    
    const { identifiant, nom, prenom, age, mail, mdp, confirmMdp } = req.body;

    // Validation des champs requis
    if (!identifiant || !nom || !prenom || !age || !mail || !mdp || !confirmMdp) {
        console.log('Champs manquants:', { identifiant, nom, prenom, age, mail, mdp, confirmMdp });
        return res.status(400).json({ message: 'Veuillez entrer toutes les informations demandées.' });
    }

    // Vérification que les mots de passe correspondent
    if (mdp !== confirmMdp) {
        return res.status(401).json({ message: 'Le mot de passe de confirmation est différent du mot de passe.' });
    }

    const hashedPassword = crypto.createHash('sha256').update(mdp).digest('hex');
    
    // Étape 1 : Insérer dans la table Entite
    let query = 'INSERT INTO Entite (IdEntite, Identifiant, Nom, mdp, Mail) VALUES (NULL, ?, ?, ?, ?)';
    connection.query(query, [identifiant, nom, hashedPassword, mail], (err, insertResults) => {
        if (err) {
            console.error('Erreur SQL (Entite insert):', err);
            return res.status(500).json({ message: 'Erreur lors de l\'insertion dans Entite: ' + err.message });
        }

        console.log('Entite insérée avec ID:', insertResults.insertId);

        // Étape 2 : Récupérer l'IdEntite (utiliser directement insertId)
        const idEntite = insertResults.insertId;

        // Étape 3 : Insérer dans la table Client
        query = 'INSERT INTO Client (IdClient, Prenom, Age, IdEntite) VALUES (NULL, ?, ?, ?)';
        connection.query(query, [prenom, age, idEntite], (err, clientResults) => {
            if (err) {
                console.error('Erreur SQL (Client insert):', err);
                return res.status(500).json({ message: 'Erreur lors de l\'insertion dans Client: ' + err.message });
            }

            console.log('Client inséré avec ID:', clientResults.insertId);
            return res.status(200).json({ message: 'Inscription réussie!' });
        });
    });
});

app.listen(port, () => {
    console.log(`Serveur backend opérationnel : http://localhost:${port}`);
});