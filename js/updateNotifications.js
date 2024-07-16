buttonAllIsRead = document.getElementById("allIsRead");
function allIsRead(complement) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', complement+'php/admin/updateNotifications.php', true);
    xhr.send();

    window.location.reload();
}