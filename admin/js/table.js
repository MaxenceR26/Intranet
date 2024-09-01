// Déclarer une variable globale pour stocker l'ID de l'utilisateur
let selectedUserId = null;

document.addEventListener("DOMContentLoaded", function() {
    const editIcons = document.querySelectorAll("#animated-table tbody tr td .fa-pen");
    const modal = document.getElementById("myModal");
    const closeButton = document.querySelector(".close");

    editIcons.forEach(icon => {
        icon.addEventListener("click", function(event) {
            event.stopPropagation();

            // Récupérer l'ID de l'utilisateur dans la première colonne de la ligne parente
            selectedUserId = this.closest("tr").cells[0].textContent;
            
            const firstname = this.closest("tr").cells[1].textContent;
            const lastname = this.closest("tr").cells[2].textContent;
            const username = firstname + ' ' + lastname;
            
            const modalTitle = document.getElementById("modalTitle");
            modalTitle.textContent = "Modifier les rôles de " + username;

            modal.style.display = "flex";
        });
    });

    closeButton.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });

    const roleForm = document.getElementById("roleForm");
    roleForm.addEventListener("submit", function(event) {
        event.preventDefault();
        const roles = document.getElementById("select").value;

        const formData = new FormData();
        formData.append('userId', selectedUserId); // Envoyer l'ID de l'utilisateur
        formData.append('select', roles);

        fetch('../php/admin/setRoles.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(data => {
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
        });

        modal.style.display = "none";
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const columns = Array.from({ length: 8 }, (_, i) =>
      document.querySelectorAll(`tbody td:nth-child(${i + 1})`)
    );
  
    columns.forEach((col, i) => {
      col.forEach((cell, j) => {
        setTimeout(() => {
          cell.style.opacity = "1";
          cell.style.transform = "translateY(0)";
        }, (i + j * 1.5) * 50);
      });
    });
  });