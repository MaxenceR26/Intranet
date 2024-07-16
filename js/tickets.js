let color = "";
function setColor(button, newColor) {
    var buttons = document.querySelectorAll('.me-3');
    buttons.forEach(function(btn) {
        btn.classList.remove('yellow', 'orange', 'red');
    });
    button.classList.add(newColor);
    color = newColor;
}

document.getElementById('send-newtickets-page').addEventListener('click', function() {
    var obj = document.getElementById('objet-newtickets-page').value;
    var np = document.getElementById('nomPrenom-newtickets-page').value;
    var exp = document.getElementById('exp-newtickets-page').value;
    $.post('../php/Sample/newTicketsClients.php', { objet: obj, np: np, explication: exp, color: color }, function(response) {
        if (response == 'Ticket fait') {
            launch_toast();
            setTimeout(() => {
                window.location.href = "../intranet.php";
            }, 2300);
        } else {
            alert("Une erreur est survenue. Veuillez contacter le service informatique.");
        }
    });
});