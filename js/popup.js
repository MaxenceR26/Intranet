document.addEventListener("DOMContentLoaded", function() {
    var openModalLink = document.getElementById("openModalLink-users-page");
    var modal = document.getElementById("myModal-users-page");

    openModalLink.addEventListener("click", function(event) {
        event.preventDefault(); 
        modal.style.display = "block";
    });

    var closeButton = document.getElementsByClassName("close-users-page")[0];
    closeButton.addEventListener("click", function() {
        modal.style.display = "none";
    });
    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});