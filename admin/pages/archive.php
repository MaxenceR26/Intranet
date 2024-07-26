<?php
require '../../php/Sample/core/connection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" href="../../images/1719307876_logo.ico"/>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../../CSS/styles.css">
    <link rel="stylesheet" href="../../CSS/panel-nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
</head>
<style>
    .delButton {
        background-color: #F24142;
        color: #f9f9f9;
        cursor: pointer;
        &:hover {
            background-color: #F05B5C;
            transition: all .5s;
        }  
    }
    .delAllButton {
        border: none;
        background-color: #F24142;
        color: #f9f9f9;
        cursor: pointer;
        width: 15%;
        height: 5vh;
        font-weight: 600;

        &:hover {
            background-color: #F05B5C;
            transition: all .5s;
        }
    }

    .btnBox {
        display: flex;
        justify-content: end;
        margin: 50px;
    }
</style>
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
            <li><a href="communes.php">Annuaire mairies</a></li>
            <li><a href="services.php">Annuaire services</a></li>
            <?php
            if ($_SESSION["idroles"] == "3") {
                echo "<li><a href='tickets.php'>Tickets</a></li>
                <li><a class='active' href=''>Tickets Archivés</a></li>";
            }

            ?>
            <li><a href="../../intranet.php">Intranet</a></li>
        </ul>
    </nav>
    <div class="box">
        <table id="animated-table">
            <thead>
                <tr>
                <th>Utilisateur</th>
                <th>Page</th>
                <th>Raison</th>
                <th>Date de création</th>
                <th>Identifiants</th>
                <th></th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT * FROM tickets WHERE etat = '1'";
                $result = $mysqli->query($sql);
                $found = false;

                if ($result) {
                    if ($result->num_rows > 0) {
                        $found = false; // Initialisez le drapeau à false
                
                        while ($row = $result->fetch_assoc()) {
                            $raison = $row['raison'];
                            $pages = $row['pages'];
                            $utilisateurs = $row['utilisateurs'];
                            $identifiant = $row['foreignkey'];
                            $date_creation = $row['date_creation'];
                
                            if ($row['etat'] != '0') {
                                $found = true; // Un ticket avec un état différent de 1 a été trouvé
                
                                echo "<tr>
                                <td>$utilisateurs</td>
                                <td id='page'>$pages</td>
                                <td>$raison</td>
                                <td>$date_creation</td>
                                <td>$identifiant</td>
                                <td class='delButton' style='width: 100px'>Supprimer</td>";
                                echo "</tr>";
                            }
                        }
                
                        // Après avoir parcouru tous les tickets, vérifiez le drapeau
                        if (!$found) {
                            echo "<tr><td colspan='8'>Aucun ticket trouvé.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Aucun ticket trouvé.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Erreur dans l'exécution de la requête : " . $mysqli->error . "</td></tr>";
                }

                $mysqli->close();
                
                ?>
            </tbody>
        </table>
    </div>
    <?php 
    
    if ($found) {
        echo "<div class='btnBox'>
        <button class='delAllButton'>Tout supprimer</button>
    </div>";
    }
    
    ?>
    
    
</body>
<script>


    document.addEventListener("DOMContentLoaded", function () {
        const delButton = document.querySelectorAll(".delButton");
        const delAllButton = document.querySelectorAll(".delAllButton");
        delButton.forEach(del => {
            del.addEventListener("click", function(event) {
                event.stopPropagation();
                const row = this.parentElement;
                const identifiants = row.children[4].textContent;
                var res = confirm("Êtes-vous sûr de vouloir supprimer définitivement?");
                
                if (res) {
                    $.post('delTickets.php', { identifiants: identifiants, current_page:'archive' }, function(response) {
                        if (response.includes("Ticket supprimé avec succès")) {
                            window.location.reload();
                        } else {
                            alert("Erreur lors de la suppression du ticket: " + response);
                        }
                    })
                }
                
            })
        })

        delAllButton.forEach(del => {
            del.addEventListener("click", function(event) {
                event.stopPropagation();
                const row = this.parentElement;
                var res = confirm("Êtes-vous sûr de vouloir supprimer tout les tickets archivés définitivement?");
                
                if (res) {
                    $.post('delallTickets.php', {}, function(response) {
                        if (response.includes("Tickets supprimés avec succès")) {
                            window.location.reload();
                        } else {
                            alert("Erreur lors de la suppression du ticket: " + response);
                        }
                    })
                }
                
            })
        })

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