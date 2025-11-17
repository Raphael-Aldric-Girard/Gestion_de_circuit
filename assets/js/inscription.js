document.querySelector('inscripton').addEventListener('submit', function (event) {
    event.preventDefault();
    document.querySelector('inscription').addEventListener('submit', function () {
        let mdp = document.getElementById('mdp').value;
        let confirmMdp = document.getElementById('confirm_mdp').value;
        if (mdp === confirmMdp) {
            document.querySelector('inscription').submit();
            alert('Inscription terminée');
        }
        else {
            alert('Le mot de passe de confirmation ne correspond pas au mot de passe saisie')
        }
    });
})
