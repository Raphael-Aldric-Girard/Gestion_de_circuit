window.onload = function () {
    // Vérifie si le cookie n'existe PAS ou est vide
    if (!getCookie('user_name') || getCookie('user_name') === '') {
        window.location.href = '../html/authentification.html';
    }
}