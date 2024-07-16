function deconnexion(complement) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', complement+'php/Sample/core/deconnexion.php', true);
    xhr.send();

    window.location.href = complement+'index.html';
}