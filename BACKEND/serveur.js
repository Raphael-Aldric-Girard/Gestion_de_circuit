import { getCookie } from '../assets/js/redirectionInfoUser';

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

app.get('/vehicule/voiture', (req, res => {
    let query = 'SELECT '
}))