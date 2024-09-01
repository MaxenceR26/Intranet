<?php
session_start();
$current_page = $_SERVER['PHP_SELF'];
if (!isset($_SESSION['state'])) {
    header('Location: ..\..\index.html');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/panel-nav.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../../CSS/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <title>Admin Panel</title>
    <link rel="icon" href="../../images/1719307876_logo.ico"/>
</head>
<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <label class="logo">Panel Admin</label>
        <ul>
        <?php
            if ($_SESSION["idroles"] == "3") {
                echo "<li><a href='../index.php'>Gestion utilisateurs</a></li>";
            }
        ?>
            <li><a href="newActualites.php">Ajouter une actualité</a></li>
            <li><a href="municipalities.php">Annuaire mairies</a></li>
            <li><a class="active" href="services.php">Annuaire services</a></li>
            <?php
            if ($_SESSION["idroles"] == "3") {
                echo "<li><a href='tickets.php'>Tickets</a></li>
                <li><a href='archive.php'>Tickets Archivés</a></li>";
            }
            ?>
            <li><a href="../../intranet.php">Intranet</a></li>
        </ul>
    </nav>
    <div class="box">
        <?php
        require_once '../../php/Sample/core/connection.php';

        $sql = "SELECT * FROM services";
        $result = $mysqli->query($sql);

        if ($result && $result->num_rows > 0) {
            echo "<table id='animated-table'>
                <thead>
                    <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Poste</th>
                    <th>Numéro Port</th>
                    <th>Numéro Ext</th>
                    <th>DirFixe</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody>";
            
            while ($row = $result->fetch_assoc()) {
                $nom = $row['nom'];
                $prenom = $row['prenom'];
                $email = $row['email'];
                $poste = $row['poste'];
                $numPro = $row['numPort'];
                $numExt = $row['numExt'];
                $dirFixe = $row['dirFixe'];

                echo "<tr data-nom='$nom' data-prenom='$prenom'>
                    <td>$nom</td>
                    <td>$prenom</td>
                    <td>$email</td>
                    <td>$poste</td>
                    <td>$numPro</td>
                    <td>$numExt</td>
                    <td>$dirFixe</td>
                    <td class='iconButton'><i class='fa-solid fa-pen'></i></td>
                </tr>";
            }

            echo "</tbody></table>";
            echo "<a id='removeButton' href='#'>Supprimer l'actualité</a>";
            echo "<div id='myModal' class='modal'>
                <div class='modal-content'>
                    <span class='close'>&times;</span>
                    <div class='elementModal'>
                        <h2 id='modalTitle'>Modification de l'annuaire</h2>
                        <form action='../../php/admin/addtickets.php' id='roleForm' method='POST'>
                            <input type='text' name='email' id='email' placeholder='E-mail'>
                            <input type='text' name='numero' placeholder='Numéro Portable'>
                            <input type='text' name='numeroext' placeholder='Numéro Extérieur'>
                            <input type='text' name='dirfixe' placeholder='DirFixe'>
                            <input type='text' name='raison' maxlength='80' placeholder='Raison' required>
                            <input type='hidden' name='page' value='services'>
                            <button type='submit'>Valider</button>
                        </form>
                    </div>
                </div>
            </div>";
        } else {
            echo "<p>Aucun services trouvé.</p>";
        }

        $mysqli->close();
        ?>
    </div>
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const editIcons = document.querySelectorAll("#animated-table tbody tr td .fa-pen");
    const modal = document.getElementById("myModal");
    const closeButton = document.querySelector(".close");

    editIcons.forEach(icon => {
        icon.addEventListener("click", function(event) {
            event.stopPropagation();
            
            const nom = this.closest("tr").dataset.nom;
            const prenom = this.closest("tr").dataset.prenom;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "storeUserDataInSession.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Données utilisateur stockées dans la session avec succès");
                } else {
                    console.error("Erreur lors du stockage des données utilisateur dans la session");
                }
            };
            xhr.send("nom=" + encodeURIComponent(nom) + "&prenom=" + encodeURIComponent(prenom));

            modal.style.display = "flex";
        });
    });

    closeButton.addEventListener("click", function() {
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
</script>
</html>
